<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\PublicHoliday;
use App\Models\ParentalLeaveRecord;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveManagementController extends Controller
{
    /**
     * Leave management dashboard
     */
    public function index()
    {
        $currentYear = now()->year;

        $stats = [
            'pending_requests' => LeaveRequest::where('status', 'pending')->count(),
            'approved_today' => LeaveRequest::whereDate('approved_at', today())->count(),
            'on_leave_today' => LeaveRequest::where('status', 'approved')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count(),
            'upcoming_holidays' => PublicHoliday::upcoming()->limit(5)->count(),
        ];

        $pendingRequests = LeaveRequest::with(['employee', 'leaveType'])
            ->where('status', 'pending')
            ->orderBy('start_date')
            ->limit(10)
            ->get();

        $upcomingHolidays = PublicHoliday::upcoming()->limit(5)->get();

        $employeesOnLeave = LeaveRequest::with('employee')
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->get();

        return view('leave-management.index', compact('stats', 'pendingRequests', 'upcomingHolidays', 'employeesOnLeave'));
    }

    /**
     * Leave types management
     */
    public function types()
    {
        $types = LeaveType::withCount('leaveBalances')->orderBy('sort_order')->get();

        return view('leave-management.types.index', compact('types'));
    }

    /**
     * Create leave type form
     */
    public function createType()
    {
        return view('leave-management.types.create');
    }

    /**
     * Store leave type
     */
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:leave_types',
            'description' => 'nullable|string',
            'default_days_per_year' => 'required|integer|min:0',
            'is_paid' => 'boolean',
            'requires_approval' => 'boolean',
            'requires_documentation' => 'boolean',
            'accrual_method' => 'required|in:annual,monthly,per_period,tenure_based',
            'accrual_rate' => 'nullable|numeric|min:0',
            'can_carry_over' => 'boolean',
            'max_carry_over_days' => 'nullable|integer|min:0',
            'carry_over_expiry_months' => 'nullable|integer|min:0',
            'min_service_days' => 'required|integer|min:0',
            'sort_order' => 'nullable|integer',
        ]);

        LeaveType::create($validated);

        return redirect()->route('leave-management.types')
            ->with('success', 'Leave type created successfully.');
    }

    /**
     * Edit leave type
     */
    public function editType(LeaveType $type)
    {
        return view('leave-management.types.edit', compact('type'));
    }

    /**
     * Update leave type
     */
    public function updateType(Request $request, LeaveType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:leave_types,code,' . $type->id,
            'description' => 'nullable|string',
            'default_days_per_year' => 'required|integer|min:0',
            'is_paid' => 'boolean',
            'requires_approval' => 'boolean',
            'requires_documentation' => 'boolean',
            'accrual_method' => 'required|in:annual,monthly,per_period,tenure_based',
            'accrual_rate' => 'nullable|numeric|min:0',
            'can_carry_over' => 'boolean',
            'max_carry_over_days' => 'nullable|integer|min:0',
            'carry_over_expiry_months' => 'nullable|integer|min:0',
            'min_service_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $type->update($validated);

        return redirect()->route('leave-management.types')
            ->with('success', 'Leave type updated successfully.');
    }

    /**
     * Employee leave balances
     */
    public function balances(Request $request)
    {
        $year = $request->get('year', now()->year);

        $balances = LeaveBalance::with(['employee', 'leaveType'])
            ->where('year', $year)
            ->orderBy('employee_id')
            ->paginate(20);

        $years = LeaveBalance::distinct()->pluck('year')->sort()->reverse();

        return view('leave-management.balances.index', compact('balances', 'year', 'years'));
    }

    /**
     * Initialize balances for all employees
     */
    public function initializeBalances(Request $request)
    {
        $year = $request->get('year', now()->year);
        $employees = Employee::where('status', 'active')->get();
        $leaveTypes = LeaveType::active()->get();

        foreach ($employees as $employee) {
            foreach ($leaveTypes as $type) {
                $balance = LeaveBalance::getOrCreate($employee->id, $type->id, $year);

                if ($balance->entitled_days == 0) {
                    $entitledDays = $type->calculateEntitledDays($employee);
                    $balance->update([
                        'entitled_days' => $entitledDays,
                        'available_days' => $entitledDays + $balance->carried_over_days,
                    ]);
                }
            }
        }

        return back()->with('success', "Leave balances initialized for {$employees->count()} employees.");
    }

    /**
     * Adjust employee balance
     */
    public function adjustBalance(Request $request, LeaveBalance $balance)
    {
        $validated = $request->validate([
            'adjustment_days' => 'required|numeric',
            'notes' => 'required|string',
        ]);

        $balance->adjustment_days += $validated['adjustment_days'];
        $balance->notes = ($balance->notes ? $balance->notes . "\n" : '') .
            date('Y-m-d') . ": Adjusted {$validated['adjustment_days']} days - {$validated['notes']}";
        $balance->save();
        $balance->recalculateAvailable();

        return back()->with('success', 'Balance adjusted successfully.');
    }

    /**
     * Public holidays management
     */
    public function holidays(Request $request)
    {
        $year = $request->get('year', now()->year);

        $holidays = PublicHoliday::forYear($year)->get();

        $years = PublicHoliday::distinct()->pluck('year')->sort()->reverse();

        return view('leave-management.holidays.index', compact('holidays', 'year', 'years'));
    }

    /**
     * Seed Jamaica holidays for a year
     */
    public function seedHolidays(Request $request)
    {
        $year = $request->get('year', now()->year);

        PublicHoliday::seedJamaicaHolidays($year);

        return back()->with('success', "Jamaica public holidays seeded for {$year}.");
    }

    /**
     * Create holiday form
     */
    public function createHoliday()
    {
        return view('leave-management.holidays.create');
    }

    /**
     * Store holiday
     */
    public function storeHoliday(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_observed' => 'boolean',
            'observed_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $validated['year'] = Carbon::parse($validated['date'])->year;

        PublicHoliday::create($validated);

        return redirect()->route('leave-management.holidays')
            ->with('success', 'Holiday added successfully.');
    }

    /**
     * Parental leave records
     */
    public function parentalLeave()
    {
        $records = ParentalLeaveRecord::with('employee')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pending = ParentalLeaveRecord::pending()->count();
        $active = ParentalLeaveRecord::active()->count();

        return view('leave-management.parental.index', compact('records', 'pending', 'active'));
    }

    /**
     * Create parental leave form
     */
    public function createParentalLeave()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('leave-management.parental.create', compact('employees'));
    }

    /**
     * Store parental leave
     */
    public function storeParentalLeave(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:maternity,paternity,adoption',
            'expected_date' => 'required|date',
            'leave_start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        if ($validated['leave_type'] === 'maternity') {
            $record = ParentalLeaveRecord::createMaternityLeave(
                $employee,
                $validated['expected_date'],
                $validated['leave_start_date']
            );
        } else {
            $record = ParentalLeaveRecord::createPaternityLeave(
                $employee,
                $validated['expected_date'],
                $validated['leave_start_date']
            );
        }

        if ($validated['notes']) {
            $record->update(['notes' => $validated['notes']]);
        }

        return redirect()->route('leave-management.parental')
            ->with('success', ucfirst($validated['leave_type']) . ' leave record created.');
    }

    /**
     * Activate parental leave
     */
    public function activateParentalLeave(Request $request, ParentalLeaveRecord $record)
    {
        $validated = $request->validate([
            'actual_date' => 'nullable|date',
        ]);

        $record->update([
            'status' => 'active',
            'actual_date' => $validated['actual_date'],
        ]);

        return back()->with('success', 'Parental leave activated.');
    }

    /**
     * Complete parental leave
     */
    public function completeParentalLeave(ParentalLeaveRecord $record)
    {
        $record->update(['status' => 'completed']);

        return back()->with('success', 'Parental leave marked as completed.');
    }

    /**
     * Calculate working days between dates (excluding weekends and holidays)
     */
    public function calculateWorkingDays(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $workingDays = 0;
        $holidayCount = 0;
        $current = $startDate->copy();

        while ($current <= $endDate) {
            if (!$current->isWeekend()) {
                if (PublicHoliday::isHoliday($current)) {
                    $holidayCount++;
                } else {
                    $workingDays++;
                }
            }
            $current->addDay();
        }

        return response()->json([
            'total_days' => $startDate->diffInDays($endDate) + 1,
            'working_days' => $workingDays,
            'weekends' => $startDate->diffInDays($endDate) + 1 - $workingDays - $holidayCount,
            'holidays' => $holidayCount,
        ]);
    }

    /**
     * Employee leave summary
     */
    public function employeeSummary(Employee $employee)
    {
        $year = now()->year;

        $balances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employee->id)
            ->where('year', $year)
            ->get();

        $recentRequests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $parentalRecords = ParentalLeaveRecord::where('employee_id', $employee->id)->get();

        return view('leave-management.employee', compact('employee', 'balances', 'recentRequests', 'parentalRecords', 'year'));
    }

    /**
     * Seed Jamaica default leave types
     */
    public function seedLeaveTypes()
    {
        $types = [
            [
                'name' => 'Vacation Leave',
                'code' => 'VACATION',
                'description' => 'Annual paid vacation leave. 10 days after 1 year, 15 days after 10 years.',
                'default_days_per_year' => 10,
                'is_paid' => true,
                'accrual_method' => 'tenure_based',
                'can_carry_over' => true,
                'max_carry_over_days' => 5,
                'min_service_days' => 110,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sick Leave',
                'code' => 'SICK',
                'description' => 'Paid sick leave. Accrues 1 day per 22 days worked after 110 days. 10 days after 1 year.',
                'default_days_per_year' => 10,
                'is_paid' => true,
                'requires_documentation' => true,
                'accrual_method' => 'tenure_based',
                'can_carry_over' => false,
                'min_service_days' => 110,
                'sort_order' => 2,
            ],
            [
                'name' => 'Maternity Leave',
                'code' => 'MATERNITY',
                'description' => '12 weeks (60 working days). 8 weeks paid at full salary. Must start 2 weeks before due date.',
                'default_days_per_year' => 60,
                'is_paid' => true,
                'requires_documentation' => true,
                'accrual_method' => 'annual',
                'can_carry_over' => false,
                'min_service_days' => 365,
                'sort_order' => 3,
            ],
            [
                'name' => 'Paternity Leave',
                'code' => 'PATERNITY',
                'description' => '20 working days paid leave for fathers after birth of child.',
                'default_days_per_year' => 20,
                'is_paid' => true,
                'requires_documentation' => true,
                'accrual_method' => 'annual',
                'can_carry_over' => false,
                'min_service_days' => 0,
                'sort_order' => 4,
            ],
            [
                'name' => 'Bereavement Leave',
                'code' => 'BEREAVEMENT',
                'description' => 'Leave for death of immediate family member.',
                'default_days_per_year' => 5,
                'is_paid' => true,
                'requires_documentation' => true,
                'accrual_method' => 'annual',
                'can_carry_over' => false,
                'min_service_days' => 0,
                'sort_order' => 5,
            ],
            [
                'name' => 'Unpaid Leave',
                'code' => 'UNPAID',
                'description' => 'Unpaid personal leave.',
                'default_days_per_year' => 30,
                'is_paid' => false,
                'accrual_method' => 'annual',
                'can_carry_over' => false,
                'min_service_days' => 0,
                'sort_order' => 6,
            ],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(['code' => $type['code']], $type);
        }

        return back()->with('success', 'Jamaica default leave types seeded successfully.');
    }
}
