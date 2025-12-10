<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Setting;

/**
 * PayrollCalculator - Implements Jamaican Statutory Deductions
 *
 * Supports:
 * - Fortnightly and Monthly pay frequencies
 * - Salaried, Hourly (from salary), and Flexi-hour pay types
 *
 * Tax Rates (2025):
 * - NHT (National Housing Trust): Employee 2%, Employer 3%
 * - NIS (National Insurance Scheme): Employee 3%, Employer 3% (Capped at JMD $5M/year)
 * - Education Tax: Employee 2.25%, Employer 3.5%
 * - HEART Trust/NTA: Employer 3% only
 * - Income Tax (PAYE): 25% on income over threshold
 *
 * Income Tax Thresholds (2025):
 * - Annual threshold: JMD $1,500,096 (approx JMD $125,008/month)
 * - Rate: 25% on income above threshold
 */
class PayrollCalculator
{
    // Employee contribution rates
    const NHT_EMPLOYEE_RATE = 0.02;      // 2%
    const NIS_EMPLOYEE_RATE = 0.03;      // 3%
    const ED_TAX_EMPLOYEE_RATE = 0.0225; // 2.25%

    // Employer contribution rates
    const NHT_EMPLOYER_RATE = 0.03;      // 3%
    const NIS_EMPLOYER_RATE = 0.03;      // 3%
    const ED_TAX_EMPLOYER_RATE = 0.035;  // 3.5%
    const HEART_EMPLOYER_RATE = 0.03;    // 3%

    // NIS Annual Cap (JMD)
    const NIS_ANNUAL_CAP = 5000000;

    // Income Tax (PAYE)
    const INCOME_TAX_THRESHOLD_ANNUAL = 1500096;
    const INCOME_TAX_RATE = 0.25; // 25%

    // Overtime rates
    const OVERTIME_RATE = 1.5;
    const DOUBLE_TIME_RATE = 2.0;

    /**
     * Calculate gross pay for an employee based on their pay type
     */
    public function calculateGrossPay(
        Employee $employee,
        ?float $hoursWorked = null,
        ?float $overtimeHours = null
    ): array {
        $payType = $employee->pay_type ?? 'salaried';
        $payFrequency = $employee->pay_frequency ?? 'monthly';

        $breakdown = [
            'pay_type' => $payType,
            'pay_frequency' => $payFrequency,
            'hours_worked' => null,
            'hourly_rate' => null,
            'regular_hours' => null,
            'regular_pay' => 0,
            'overtime_hours' => $overtimeHours ?? 0,
            'overtime_pay' => 0,
            'basic_salary' => 0,
            'gross_pay' => 0,
        ];

        switch ($payType) {
            case Employee::PAY_TYPE_HOURLY_FIXED:
            case Employee::PAY_TYPE_HOURLY_FROM_SALARY:
                // Hourly calculation
                $hourlyRate = $employee->calculateHourlyRate();
                $standardHours = $employee->getDefaultStandardHours();
                $actualHours = $hoursWorked ?? $standardHours;

                // Split into regular and overtime if hours exceed standard
                $regularHours = min($actualHours, $standardHours);
                $autoOvertimeHours = max(0, $actualHours - $standardHours);
                $totalOvertimeHours = $autoOvertimeHours + ($overtimeHours ?? 0);

                $regularPay = round($hourlyRate * $regularHours, 2);
                $overtimePay = round($hourlyRate * $totalOvertimeHours * self::OVERTIME_RATE, 2);

                $breakdown['hours_worked'] = $actualHours;
                $breakdown['hourly_rate'] = $hourlyRate;
                $breakdown['regular_hours'] = $regularHours;
                $breakdown['regular_pay'] = $regularPay;
                $breakdown['overtime_hours'] = $totalOvertimeHours;
                $breakdown['overtime_pay'] = $overtimePay;
                $breakdown['basic_salary'] = $regularPay;
                $breakdown['gross_pay'] = $regularPay + $overtimePay;
                break;

            case Employee::PAY_TYPE_SALARIED:
            default:
                // Fixed salary per period
                $basicSalary = $payFrequency === 'fortnightly'
                    ? $employee->fortnightly_salary
                    : $employee->monthly_salary;

                // Calculate overtime if provided
                $overtimePay = 0;
                if ($overtimeHours && $overtimeHours > 0) {
                    $hourlyRate = $employee->calculateHourlyRate();
                    $overtimePay = round($hourlyRate * $overtimeHours * self::OVERTIME_RATE, 2);
                    $breakdown['hourly_rate'] = $hourlyRate;
                    $breakdown['overtime_hours'] = $overtimeHours;
                    $breakdown['overtime_pay'] = $overtimePay;
                }

                $breakdown['basic_salary'] = $basicSalary;
                $breakdown['gross_pay'] = $basicSalary + $overtimePay;
                break;
        }

        return $breakdown;
    }

    /**
     * Calculate payroll for an employee for a given period
     */
    public function calculate(Employee $employee, float $grossPay, ?float $annualEarningsToDate = 0): array
    {
        // Calculate employee deductions
        $nhtEmployee = $this->calculateNHT($grossPay, 'employee');
        $nisEmployee = $this->calculateNIS($grossPay, $annualEarningsToDate, 'employee');
        $edTaxEmployee = $this->calculateEducationTax($grossPay, 'employee');

        // Calculate employer contributions
        $nhtEmployer = $this->calculateNHT($grossPay, 'employer');
        $nisEmployer = $this->calculateNIS($grossPay, $annualEarningsToDate, 'employer');
        $edTaxEmployer = $this->calculateEducationTax($grossPay, 'employer');
        $heartEmployer = $this->calculateHEART($grossPay);

        // Calculate income tax (PAYE)
        $incomeTax = $this->calculateIncomeTax($grossPay, $annualEarningsToDate);

        // Calculate total deductions and net pay
        $totalEmployeeDeductions = $nhtEmployee + $nisEmployee + $edTaxEmployee + $incomeTax;
        $netPay = $grossPay - $totalEmployeeDeductions;

        return [
            'gross_pay' => round($grossPay, 2),
            'nht_employee' => round($nhtEmployee, 2),
            'nht_employer' => round($nhtEmployer, 2),
            'nis_employee' => round($nisEmployee, 2),
            'nis_employer' => round($nisEmployer, 2),
            'ed_tax_employee' => round($edTaxEmployee, 2),
            'ed_tax_employer' => round($edTaxEmployer, 2),
            'heart_employer' => round($heartEmployer, 2),
            'income_tax' => round($incomeTax, 2),
            'total_employee_deductions' => round($totalEmployeeDeductions, 2),
            'total_employer_contributions' => round($nhtEmployer + $nisEmployer + $edTaxEmployer + $heartEmployer, 2),
            'net_pay' => round($netPay, 2),
        ];
    }

    /**
     * Full payroll calculation including gross pay calculation
     */
    public function calculateFull(
        Employee $employee,
        \DateTime $periodStart,
        \DateTime $periodEnd,
        ?float $hoursWorked = null,
        ?float $overtimeHours = null,
        ?float $allowances = 0,
        ?float $bonus = 0,
        ?float $commission = 0,
        ?float $otherEarnings = 0,
        ?float $loanDeduction = 0,
        ?float $otherDeductions = 0,
        ?float $annualEarningsToDate = 0
    ): array {
        // Calculate gross pay based on employee's pay type
        $grossBreakdown = $this->calculateGrossPay($employee, $hoursWorked, $overtimeHours);

        // Add additional earnings to gross
        $additionalEarnings = $allowances + $bonus + $commission + $otherEarnings;
        $totalGross = $grossBreakdown['gross_pay'] + $additionalEarnings;

        // Calculate statutory deductions
        $deductions = $this->calculate($employee, $totalGross, $annualEarningsToDate);

        // Apply additional deductions
        $totalDeductions = $deductions['total_employee_deductions'] + $loanDeduction + $otherDeductions;
        $netPay = $totalGross - $totalDeductions;

        return array_merge($deductions, [
            // Pay type info
            'pay_type' => $grossBreakdown['pay_type'],
            'pay_frequency' => $grossBreakdown['pay_frequency'],

            // Hours tracking
            'hours_worked' => $grossBreakdown['hours_worked'],
            'hourly_rate_used' => $grossBreakdown['hourly_rate'],
            'regular_hours' => $grossBreakdown['regular_hours'],
            'regular_pay' => $grossBreakdown['regular_pay'],

            // Earnings breakdown
            'basic_salary' => $grossBreakdown['basic_salary'],
            'overtime_hours' => $grossBreakdown['overtime_hours'],
            'overtime_pay' => $grossBreakdown['overtime_pay'],
            'allowances' => round($allowances, 2),
            'bonus' => round($bonus, 2),
            'commission' => round($commission, 2),
            'other_earnings' => round($otherEarnings, 2),
            'gross_pay' => round($totalGross, 2),

            // Additional deductions
            'loan_deduction' => round($loanDeduction, 2),
            'other_deductions' => round($otherDeductions, 2),

            // Final amounts
            'net_pay' => round($netPay, 2),

            // Calculation breakdown for audit
            'calculation_breakdown' => [
                'method' => $grossBreakdown['pay_type'],
                'frequency' => $grossBreakdown['pay_frequency'],
                'hourly_rate' => $grossBreakdown['hourly_rate'],
                'hours_worked' => $grossBreakdown['hours_worked'],
                'regular_hours' => $grossBreakdown['regular_hours'],
                'overtime_rate' => self::OVERTIME_RATE,
                'calculated_at' => now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Calculate NHT (National Housing Trust) contribution
     */
    public function calculateNHT(float $grossPay, string $type = 'employee'): float
    {
        $rate = $type === 'employer' ? self::NHT_EMPLOYER_RATE : self::NHT_EMPLOYEE_RATE;
        return $grossPay * $rate;
    }

    /**
     * Calculate NIS (National Insurance Scheme) contribution
     * Note: NIS has an annual cap of JMD $5M
     */
    public function calculateNIS(float $grossPay, float $annualEarningsToDate = 0, string $type = 'employee'): float
    {
        $rate = $type === 'employer' ? self::NIS_EMPLOYER_RATE : self::NIS_EMPLOYEE_RATE;

        // Check if annual cap has been reached
        $projectedAnnual = $annualEarningsToDate + $grossPay;

        if ($annualEarningsToDate >= self::NIS_ANNUAL_CAP) {
            // Already reached cap, no NIS due
            return 0;
        }

        if ($projectedAnnual > self::NIS_ANNUAL_CAP) {
            // Partial NIS - only on amount up to cap
            $taxableAmount = self::NIS_ANNUAL_CAP - $annualEarningsToDate;
            return $taxableAmount * $rate;
        }

        return $grossPay * $rate;
    }

    /**
     * Calculate Education Tax contribution
     */
    public function calculateEducationTax(float $grossPay, string $type = 'employee'): float
    {
        $rate = $type === 'employer' ? self::ED_TAX_EMPLOYER_RATE : self::ED_TAX_EMPLOYEE_RATE;
        return $grossPay * $rate;
    }

    /**
     * Calculate HEART Trust/NTA contribution (Employer only)
     */
    public function calculateHEART(float $grossPay): float
    {
        return $grossPay * self::HEART_EMPLOYER_RATE;
    }

    /**
     * Calculate Income Tax (PAYE - Pay As You Earn)
     * Jamaica uses a progressive tax system with threshold
     */
    public function calculateIncomeTax(float $grossPay, float $annualEarningsToDate = 0): float
    {
        // Calculate monthly threshold
        $monthlyThreshold = self::INCOME_TAX_THRESHOLD_ANNUAL / 12;

        // First, calculate statutory deductions that reduce taxable income
        $nht = $grossPay * self::NHT_EMPLOYEE_RATE;
        $nis = $grossPay * self::NIS_EMPLOYEE_RATE;
        $edTax = $grossPay * self::ED_TAX_EMPLOYEE_RATE;

        // Taxable income = Gross - Statutory Deductions
        $taxableGross = $grossPay - $nht - $nis - $edTax;

        // Apply threshold
        $taxableIncome = max(0, $taxableGross - $monthlyThreshold);

        // Calculate tax at 25%
        return $taxableIncome * self::INCOME_TAX_RATE;
    }

    /**
     * Create a payroll record from calculated values
     */
    public function createPayroll(
        Employee $employee,
        \DateTime $periodStart,
        \DateTime $periodEnd,
        ?float $hoursWorked = null,
        ?float $overtimeHours = null,
        ?float $allowances = 0,
        ?float $bonus = 0,
        ?float $commission = 0,
        ?float $otherEarnings = 0,
        ?float $loanDeduction = 0,
        ?float $otherDeductions = 0,
        ?float $annualEarningsToDate = 0
    ): Payroll {
        $calculation = $this->calculateFull(
            $employee,
            $periodStart,
            $periodEnd,
            $hoursWorked,
            $overtimeHours,
            $allowances,
            $bonus,
            $commission,
            $otherEarnings,
            $loanDeduction,
            $otherDeductions,
            $annualEarningsToDate
        );

        return Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'pay_frequency' => $calculation['pay_frequency'],
            'pay_type' => $calculation['pay_type'],
            'hours_worked' => $calculation['hours_worked'],
            'hourly_rate_used' => $calculation['hourly_rate_used'],
            'regular_hours' => $calculation['regular_hours'],
            'regular_pay' => $calculation['regular_pay'],
            'gross_pay' => $calculation['gross_pay'],
            'basic_salary' => $calculation['basic_salary'],
            'overtime_hours' => $calculation['overtime_hours'],
            'overtime_pay' => $calculation['overtime_pay'],
            'allowances' => $calculation['allowances'],
            'bonus' => $calculation['bonus'],
            'commission' => $calculation['commission'],
            'other_earnings' => $calculation['other_earnings'],
            'nht_employee' => $calculation['nht_employee'],
            'nht_employer' => $calculation['nht_employer'],
            'nis_employee' => $calculation['nis_employee'],
            'nis_employer' => $calculation['nis_employer'],
            'ed_tax_employee' => $calculation['ed_tax_employee'],
            'ed_tax_employer' => $calculation['ed_tax_employer'],
            'heart_employer' => $calculation['heart_employer'],
            'income_tax' => $calculation['income_tax'],
            'loan_deduction' => $calculation['loan_deduction'],
            'other_deductions' => $calculation['other_deductions'],
            'calculation_breakdown' => $calculation['calculation_breakdown'],
            'net_pay' => $calculation['net_pay'],
            'status' => 'draft',
        ]);
    }

    /**
     * Get pay period dates based on frequency
     */
    public static function getPayPeriodDates(string $frequency, ?\DateTime $referenceDate = null): array
    {
        $reference = $referenceDate ?? new \DateTime();

        if ($frequency === 'fortnightly') {
            // Calculate fortnightly period (2 weeks)
            $dayOfWeek = (int) $reference->format('N'); // 1 = Monday, 7 = Sunday
            $weekNumber = (int) $reference->format('W');

            // Determine if we're in week 1 or week 2 of the fortnight
            $isOddWeek = $weekNumber % 2 === 1;

            if ($isOddWeek) {
                // Week 1 of fortnight - start of current week
                $start = (clone $reference)->modify('monday this week');
                $end = (clone $start)->modify('+13 days'); // End of next week (Sunday)
            } else {
                // Week 2 of fortnight - start of previous week
                $start = (clone $reference)->modify('monday last week');
                $end = (clone $start)->modify('+13 days');
            }

            return [
                'start' => $start,
                'end' => $end,
                'days' => 14,
            ];
        }

        // Monthly (default)
        $start = (clone $reference)->modify('first day of this month');
        $end = (clone $reference)->modify('last day of this month');

        return [
            'start' => $start,
            'end' => $end,
            'days' => (int) $end->format('t'),
        ];
    }

    /**
     * Get summary of tax rates for display
     */
    public static function getTaxRates(): array
    {
        return [
            'nht' => [
                'employee' => self::NHT_EMPLOYEE_RATE * 100,
                'employer' => self::NHT_EMPLOYER_RATE * 100,
            ],
            'nis' => [
                'employee' => self::NIS_EMPLOYEE_RATE * 100,
                'employer' => self::NIS_EMPLOYER_RATE * 100,
                'annual_cap' => self::NIS_ANNUAL_CAP,
            ],
            'ed_tax' => [
                'employee' => self::ED_TAX_EMPLOYEE_RATE * 100,
                'employer' => self::ED_TAX_EMPLOYER_RATE * 100,
            ],
            'heart' => [
                'employee' => 0,
                'employer' => self::HEART_EMPLOYER_RATE * 100,
            ],
            'income_tax' => [
                'rate' => self::INCOME_TAX_RATE * 100,
                'threshold_annual' => self::INCOME_TAX_THRESHOLD_ANNUAL,
            ],
            'overtime' => [
                'rate' => self::OVERTIME_RATE,
                'double_time' => self::DOUBLE_TIME_RATE,
            ],
        ];
    }
}
