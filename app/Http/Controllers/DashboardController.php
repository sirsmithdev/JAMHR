<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Incident;
use App\Models\TimeEntry;
use App\Models\Appraisal;
use App\Models\StaffLoan;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Termination;
use App\Models\DisciplinaryAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Core stats
        $stats = $this->getCoreStats();

        // Role-based data
        $dashboardData = match(true) {
            $user->isAdmin() || $user->isHR() => $this->getAdminDashboard(),
            $user->isManager() => $this->getManagerDashboard($user),
            default => $this->getEmployeeDashboard($user),
        };

        return view('dashboard', array_merge($stats, $dashboardData));
    }

    private function getCoreStats(): array
    {
        return [
            'totalEmployees' => Employee::count(),
            'activeEmployees' => Employee::whereHas('user', fn($q) => $q->where('is_active', true))->count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')->count(),
            'openIncidents' => Incident::whereIn('status', ['open', 'investigating'])->count(),
        ];
    }

    private function getAdminDashboard(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Payroll summary
        $payrollThisMonth = Payroll::whereBetween('period_start', [$thisMonth, $thisMonth->copy()->endOfMonth()])
            ->sum('net_pay');
        $payrollLastMonth = Payroll::whereBetween('period_start', [$lastMonth, $lastMonth->copy()->endOfMonth()])
            ->sum('net_pay');
        $payrollChange = $payrollLastMonth > 0
            ? round((($payrollThisMonth - $payrollLastMonth) / $payrollLastMonth) * 100, 1)
            : 0;

        // Attendance today
        $todayAttendance = TimeEntry::whereDate('date', $today)->count();
        $expectedAttendance = Employee::whereHas('user', fn($q) => $q->where('is_active', true))->count();
        $attendanceRate = $expectedAttendance > 0
            ? round(($todayAttendance / $expectedAttendance) * 100)
            : 0;

        // Leave balance alerts (employees with low leave balances)
        $lowLeaveBalance = Employee::where('vacation_days_total', '>', 0)
            ->whereRaw('(vacation_days_total - vacation_days_used) <= 3')
            ->count();

        // Pending approvals
        $pendingLoans = StaffLoan::where('status', 'pending')->count();
        $pendingAppraisals = Appraisal::where('status', 'pending')->count();

        // Hiring pipeline
        $openPositions = JobPosting::where('status', 'published')->count();
        $newApplications = JobApplication::where('status', 'new')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // Recent activities
        $recentActivities = $this->getRecentActivities();

        // Upcoming events
        $upcomingBirthdays = $this->getUpcomingBirthdays();
        $upcomingAnniversaries = $this->getUpcomingAnniversaries();

        // Department breakdown
        $departmentStats = Employee::select('department', DB::raw('count(*) as count'))
            ->groupBy('department')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Monthly trends (last 6 months)
        $payrollTrend = Payroll::selectRaw("DATE_FORMAT(period_start, '%Y-%m') as month, SUM(gross_pay) as gross, SUM(net_pay) as net")
            ->where('period_start', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $leaveTrend = LeaveRequest::selectRaw("DATE_FORMAT(start_date, '%Y-%m') as month, COUNT(*) as count")
            ->where('start_date', '>=', now()->subMonths(6))
            ->where('status', 'approved')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Compliance alerts
        $complianceAlerts = $this->getComplianceAlerts();

        // Action items requiring attention
        $actionItems = collect([]);

        if (LeaveRequest::where('status', 'pending')->count() > 0) {
            $actionItems->push([
                'type' => 'leave',
                'title' => 'Pending Leave Requests',
                'count' => LeaveRequest::where('status', 'pending')->count(),
                'url' => route('leave.index'),
                'priority' => 'medium',
            ]);
        }

        if ($pendingLoans > 0) {
            $actionItems->push([
                'type' => 'loan',
                'title' => 'Pending Loan Applications',
                'count' => $pendingLoans,
                'url' => route('loans.index'),
                'priority' => 'medium',
            ]);
        }

        $overdueAppraisals = Appraisal::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(14))
            ->count();

        if ($overdueAppraisals > 0) {
            $actionItems->push([
                'type' => 'appraisal',
                'title' => 'Overdue Performance Reviews',
                'count' => $overdueAppraisals,
                'url' => route('performance.index'),
                'priority' => 'high',
            ]);
        }

        return [
            'payrollThisMonth' => $payrollThisMonth,
            'payrollChange' => $payrollChange,
            'attendanceRate' => $attendanceRate,
            'todayAttendance' => $todayAttendance,
            'expectedAttendance' => $expectedAttendance,
            'lowLeaveBalance' => $lowLeaveBalance,
            'pendingLoans' => $pendingLoans,
            'pendingAppraisals' => $pendingAppraisals,
            'openPositions' => $openPositions,
            'newApplications' => $newApplications,
            'recentActivities' => $recentActivities,
            'upcomingBirthdays' => $upcomingBirthdays,
            'upcomingAnniversaries' => $upcomingAnniversaries,
            'departmentStats' => $departmentStats,
            'payrollTrend' => $payrollTrend,
            'leaveTrend' => $leaveTrend,
            'complianceAlerts' => $complianceAlerts,
            'actionItems' => $actionItems,
        ];
    }

    private function getManagerDashboard($user): array
    {
        // Get team members (employees in same department or direct reports)
        $employee = $user->employee;
        $department = $employee?->department;

        $teamMembers = Employee::when($department, fn($q) => $q->where('department', $department))
            ->with('user')
            ->get();

        $teamIds = $teamMembers->pluck('id');

        // Team stats
        $teamLeaveRequests = LeaveRequest::whereIn('employee_id', $teamIds)
            ->where('status', 'pending')
            ->with('employee')
            ->get();

        $teamTimeEntries = TimeEntry::whereIn('employee_id', $teamIds)
            ->whereDate('date', today())
            ->get();

        $teamAppraisals = Appraisal::whereIn('employee_id', $teamIds)
            ->where('status', 'pending')
            ->with('employee')
            ->get();

        return [
            'teamMembers' => $teamMembers,
            'teamLeaveRequests' => $teamLeaveRequests,
            'teamTimeEntries' => $teamTimeEntries,
            'teamAppraisals' => $teamAppraisals,
            'payrollTrend' => collect(),
            'leaveTrend' => collect(),
            'complianceAlerts' => [],
            'actionItems' => collect(),
            'departmentStats' => collect(),
            'recentActivities' => collect(),
            'upcomingBirthdays' => collect(),
            'upcomingAnniversaries' => collect(),
        ];
    }

    private function getEmployeeDashboard($user): array
    {
        $employee = $user->employee;

        if (!$employee) {
            return [
                'myLeaveBalance' => null,
                'myTimeEntries' => collect(),
                'myPayslips' => collect(),
                'myAppraisals' => collect(),
                'payrollTrend' => collect(),
                'leaveTrend' => collect(),
                'complianceAlerts' => [],
                'actionItems' => collect(),
                'departmentStats' => collect(),
                'recentActivities' => collect(),
                'upcomingBirthdays' => collect(),
                'upcomingAnniversaries' => collect(),
            ];
        }

        // Personal leave balance
        $myLeaveBalance = [
            'vacation' => [
                'total' => $employee->vacation_days_total,
                'used' => $employee->vacation_days_used,
                'remaining' => $employee->vacation_days_remaining,
            ],
            'sick' => [
                'total' => $employee->sick_days_total,
                'used' => $employee->sick_days_used,
                'remaining' => $employee->sick_days_remaining,
            ],
        ];

        // Recent time entries
        $myTimeEntries = TimeEntry::where('employee_id', $employee->id)
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        // Recent payslips
        $myPayslips = Payroll::where('employee_id', $employee->id)
            ->orderByDesc('period_end')
            ->limit(3)
            ->get();

        // My appraisals
        $myAppraisals = Appraisal::where('employee_id', $employee->id)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        // Pending leave requests
        $myPendingLeaves = LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'pending')
            ->get();

        return [
            'myLeaveBalance' => $myLeaveBalance,
            'myTimeEntries' => $myTimeEntries,
            'myPayslips' => $myPayslips,
            'myAppraisals' => $myAppraisals,
            'myPendingLeaves' => $myPendingLeaves,
            'payrollTrend' => collect(),
            'leaveTrend' => collect(),
            'complianceAlerts' => [],
            'actionItems' => collect(),
            'departmentStats' => collect(),
            'recentActivities' => collect(),
            'upcomingBirthdays' => $this->getUpcomingBirthdays(),
            'upcomingAnniversaries' => collect(),
        ];
    }

    private function getRecentActivities(): \Illuminate\Support\Collection
    {
        $activities = collect();

        // Recent leave requests
        LeaveRequest::with('employee')
            ->latest()
            ->limit(3)
            ->get()
            ->each(function ($leave) use ($activities) {
                $activities->push([
                    'type' => 'leave',
                    'icon' => 'calendar',
                    'message' => "{$leave->employee->full_name} requested {$leave->type} leave",
                    'time' => $leave->created_at->diffForHumans(),
                    'status' => $leave->status,
                ]);
            });

        // Recent incidents
        Incident::with('reporter')
            ->latest()
            ->limit(2)
            ->get()
            ->each(function ($incident) use ($activities) {
                $activities->push([
                    'type' => 'incident',
                    'icon' => 'alert',
                    'message' => "New incident reported: {$incident->title}",
                    'time' => $incident->created_at->diffForHumans(),
                    'status' => $incident->status,
                ]);
            });

        // Recent hires
        Employee::latest()
            ->limit(2)
            ->get()
            ->each(function ($employee) use ($activities) {
                $activities->push([
                    'type' => 'employee',
                    'icon' => 'user-plus',
                    'message' => "{$employee->full_name} joined as {$employee->job_title}",
                    'time' => $employee->created_at->diffForHumans(),
                    'status' => 'info',
                ]);
            });

        return $activities->sortByDesc('time')->take(5);
    }

    private function getUpcomingBirthdays(): \Illuminate\Support\Collection
    {
        // This would require a date_of_birth field in employees table
        // For now, return empty collection
        return collect();
    }

    private function getUpcomingAnniversaries(): \Illuminate\Support\Collection
    {
        $today = Carbon::today();
        $nextMonth = $today->copy()->addMonth();

        return Employee::whereMonth('start_date', $today->month)
            ->orWhereMonth('start_date', $nextMonth->month)
            ->get()
            ->map(function ($employee) use ($today) {
                $anniversary = Carbon::parse($employee->start_date)->setYear($today->year);
                if ($anniversary->isPast()) {
                    $anniversary->addYear();
                }
                $years = $today->diffInYears($employee->start_date) + 1;

                return [
                    'employee' => $employee,
                    'date' => $anniversary,
                    'years' => $years,
                ];
            })
            ->filter(fn($item) => $item['date']->diffInDays($today) <= 30)
            ->sortBy('date')
            ->take(5);
    }

    private function getComplianceAlerts(): array
    {
        $alerts = [];
        $today = Carbon::today();

        // NIS/NHT due dates (14th of each month)
        $nisNhtDue = Carbon::create($today->year, $today->month, 14);
        if ($nisNhtDue->isPast()) {
            $nisNhtDue->addMonth();
        }
        $daysUntilNisNht = $today->diffInDays($nisNhtDue);

        if ($daysUntilNisNht <= 7) {
            $alerts[] = [
                'id' => 1,
                'title' => 'NIS/NHT Contributions Due',
                'date' => $nisNhtDue->format('M d, Y'),
                'type' => $daysUntilNisNht <= 3 ? 'urgent' : 'warning',
                'description' => "Employee and employer contributions due in {$daysUntilNisNht} days",
            ];
        }

        // PAYE due dates (14th of each month)
        if ($daysUntilNisNht <= 7) {
            $alerts[] = [
                'id' => 2,
                'title' => 'PAYE Remittance Due',
                'date' => $nisNhtDue->format('M d, Y'),
                'type' => $daysUntilNisNht <= 3 ? 'urgent' : 'warning',
                'description' => "Income tax remittance due in {$daysUntilNisNht} days",
            ];
        }

        // SO2 Form deadline (end of February)
        $so2Deadline = Carbon::create($today->year, 2, 28);
        if ($so2Deadline->isPast()) {
            $so2Deadline->addYear();
        }
        $daysUntilSo2 = $today->diffInDays($so2Deadline);

        if ($daysUntilSo2 <= 60) {
            $alerts[] = [
                'id' => 3,
                'title' => 'SO2 Forms Due',
                'date' => $so2Deadline->format('M d, Y'),
                'type' => $daysUntilSo2 <= 14 ? 'urgent' : 'info',
                'description' => "Annual SO2 forms must be issued to employees",
            ];
        }

        return $alerts;
    }
}
