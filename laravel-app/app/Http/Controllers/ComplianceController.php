<?php

namespace App\Http\Controllers;

use App\Services\PayrollCalculator;
use Illuminate\Http\Request;

class ComplianceController extends Controller
{
    public function index()
    {
        $taxRates = PayrollCalculator::getTaxRates();

        // Labor law information for Jamaica
        $laborLaws = [
            'notice_periods' => [
                ['tenure' => 'Up to 5 years', 'notice' => '2 weeks'],
                ['tenure' => '5 to 10 years', 'notice' => '4 weeks'],
                ['tenure' => '10 to 15 years', 'notice' => '6 weeks'],
                ['tenure' => 'Over 15 years', 'notice' => '8 weeks'],
            ],
            'sick_leave' => 'Employees are entitled to 10 days of paid sick leave per year after their first year of employment. For the first year, it is calculated at 1 day for every 22 days worked.',
            'vacation_leave' => 'Standard entitlement is 2 weeks (10 working days) paid vacation annually. This typically increases to 3 weeks after 10 years of service.',
            'maternity_leave' => 'Entitlement is 12 weeks maternity leave, with the first 8 weeks being paid. Employees must have worked for at least 12 months to qualify for paid leave.',
            'minimum_wage' => 'JMD $15,000 per 40-hour work week (effective June 1, 2025)',
        ];

        // Downloadable forms
        $forms = [
            ['name' => 'SO1 Form', 'description' => 'Monthly Statutory Remittance'],
            ['name' => 'P24 Form', 'description' => 'Annual Certificate of Pay'],
            ['name' => 'P45 Form', 'description' => 'Termination Certificate'],
        ];

        return view('compliance.index', compact('taxRates', 'laborLaws', 'forms'));
    }
}
