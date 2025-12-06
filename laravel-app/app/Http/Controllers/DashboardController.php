<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Incident;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $openIncidents = Incident::whereIn('status', ['open', 'investigating'])->count();

        // Get recent payroll data for chart
        $payrollData = Payroll::selectRaw('DATE_FORMAT(period_end, "%b") as month, SUM(gross_pay) as amount')
            ->where('period_end', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('period_end')
            ->get();

        // Upcoming compliance deadlines (mock data for now)
        $complianceAlerts = [
            ['id' => 1, 'title' => 'NHT Contribution Due', 'date' => now()->addDays(10)->format('M d, Y'), 'type' => 'urgent'],
            ['id' => 2, 'title' => 'Annual Returns Filing', 'date' => now()->addMonths(4)->format('M d, Y'), 'type' => 'info'],
        ];

        return view('dashboard', compact(
            'totalEmployees',
            'pendingLeaveRequests',
            'openIncidents',
            'payrollData',
            'complianceAlerts'
        ));
    }
}
