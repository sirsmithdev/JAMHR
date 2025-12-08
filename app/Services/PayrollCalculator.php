<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;

/**
 * PayrollCalculator - Implements Jamaican Statutory Deductions
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
        float $grossPay,
        ?float $annualEarningsToDate = 0
    ): Payroll {
        $calculation = $this->calculate($employee, $grossPay, $annualEarningsToDate);

        return Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'gross_pay' => $calculation['gross_pay'],
            'nht_employee' => $calculation['nht_employee'],
            'nht_employer' => $calculation['nht_employer'],
            'nis_employee' => $calculation['nis_employee'],
            'nis_employer' => $calculation['nis_employer'],
            'ed_tax_employee' => $calculation['ed_tax_employee'],
            'ed_tax_employer' => $calculation['ed_tax_employer'],
            'heart_employer' => $calculation['heart_employer'],
            'income_tax' => $calculation['income_tax'],
            'net_pay' => $calculation['net_pay'],
            'status' => 'draft',
        ]);
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
        ];
    }
}
