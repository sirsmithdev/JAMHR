<?php

namespace App\Http\Controllers;

use App\Models\AllowanceType;
use App\Models\EmployeeAllowance;
use App\Models\CompanyVehicle;
use App\Models\Employee;
use Illuminate\Http\Request;

class AllowancesController extends Controller
{
    /**
     * Allowances dashboard
     */
    public function index()
    {
        $stats = [
            'total_types' => AllowanceType::active()->count(),
            'active_allowances' => EmployeeAllowance::active()->count(),
            'monthly_total' => EmployeeAllowance::active()->get()->sum('monthly_amount'),
            'assigned_vehicles' => CompanyVehicle::assigned()->count(),
        ];

        $allowancesByType = EmployeeAllowance::active()
            ->join('allowance_types', 'employee_allowances.allowance_type_id', '=', 'allowance_types.id')
            ->selectRaw('allowance_types.name, allowance_types.category, count(*) as count, sum(employee_allowances.amount) as total')
            ->groupBy('allowance_types.id', 'allowance_types.name', 'allowance_types.category')
            ->get();

        $recentAllowances = EmployeeAllowance::with(['employee', 'allowanceType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('allowances.index', compact('stats', 'allowancesByType', 'recentAllowances'));
    }

    /**
     * Allowance types management
     */
    public function types()
    {
        $types = AllowanceType::withCount('employeeAllowances')->get();

        return view('allowances.types.index', compact('types'));
    }

    /**
     * Create allowance type form
     */
    public function createType()
    {
        return view('allowances.types.create');
    }

    /**
     * Store allowance type
     */
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:allowance_types',
            'category' => 'required|in:transport,meal,phone,housing,motor_vehicle,uniform,other',
            'description' => 'nullable|string',
            'is_taxable' => 'boolean',
            'is_fixed' => 'boolean',
            'default_amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,bi-weekly,daily,per_diem,annual',
            'tax_threshold' => 'nullable|numeric|min:0',
            'requires_receipts' => 'boolean',
        ]);

        AllowanceType::create($validated);

        return redirect()->route('allowances.types')
            ->with('success', 'Allowance type created successfully.');
    }

    /**
     * Edit allowance type
     */
    public function editType(AllowanceType $type)
    {
        return view('allowances.types.edit', compact('type'));
    }

    /**
     * Update allowance type
     */
    public function updateType(Request $request, AllowanceType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:allowance_types,code,' . $type->id,
            'category' => 'required|in:transport,meal,phone,housing,motor_vehicle,uniform,other',
            'description' => 'nullable|string',
            'is_taxable' => 'boolean',
            'is_fixed' => 'boolean',
            'default_amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,bi-weekly,daily,per_diem,annual',
            'tax_threshold' => 'nullable|numeric|min:0',
            'requires_receipts' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $type->update($validated);

        return redirect()->route('allowances.types')
            ->with('success', 'Allowance type updated successfully.');
    }

    /**
     * Assign allowance to employee
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        $types = AllowanceType::active()->get();

        return view('allowances.create', compact('employees', 'types'));
    }

    /**
     * Store employee allowance
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'allowance_type_id' => 'required|exists:allowance_types,id',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,bi-weekly,daily,per_diem,annual',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
            'notes' => 'nullable|string',
        ]);

        // Check if already has this allowance type active
        $existing = EmployeeAllowance::where('employee_id', $validated['employee_id'])
            ->where('allowance_type_id', $validated['allowance_type_id'])
            ->where('status', 'active')
            ->first();

        if ($existing) {
            return back()->with('error', 'Employee already has an active allowance of this type.');
        }

        $validated['approved_by'] = auth()->id();
        EmployeeAllowance::create($validated);

        return redirect()->route('allowances.index')
            ->with('success', 'Allowance assigned successfully.');
    }

    /**
     * Show employee allowance details
     */
    public function show(EmployeeAllowance $allowance)
    {
        $allowance->load(['employee', 'allowanceType', 'payments']);

        return view('allowances.show', compact('allowance'));
    }

    /**
     * Edit allowance
     */
    public function edit(EmployeeAllowance $allowance)
    {
        $types = AllowanceType::active()->get();

        return view('allowances.edit', compact('allowance', 'types'));
    }

    /**
     * Update allowance
     */
    public function update(Request $request, EmployeeAllowance $allowance)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,bi-weekly,daily,per_diem,annual',
            'effective_date' => 'required|date',
            'end_date' => 'nullable|date|after:effective_date',
            'status' => 'required|in:active,suspended,terminated',
            'notes' => 'nullable|string',
        ]);

        $allowance->update($validated);

        return redirect()->route('allowances.show', $allowance)
            ->with('success', 'Allowance updated successfully.');
    }

    /**
     * Terminate allowance
     */
    public function terminate(Request $request, EmployeeAllowance $allowance)
    {
        $validated = $request->validate([
            'end_date' => 'required|date',
        ]);

        $allowance->update([
            'status' => 'terminated',
            'end_date' => $validated['end_date'],
        ]);

        return back()->with('success', 'Allowance terminated.');
    }

    /**
     * Company vehicles management
     */
    public function vehicles()
    {
        $vehicles = CompanyVehicle::with('employee')->orderBy('make')->get();

        $stats = [
            'total' => $vehicles->count(),
            'assigned' => $vehicles->where('status', 'assigned')->count(),
            'available' => $vehicles->where('status', 'available')->count(),
            'maintenance' => $vehicles->where('status', 'maintenance')->count(),
        ];

        return view('allowances.vehicles.index', compact('vehicles', 'stats'));
    }

    /**
     * Create vehicle form
     */
    public function createVehicle()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('allowances.vehicles.create', compact('employees'));
    }

    /**
     * Store vehicle
     */
    public function storeVehicle(Request $request)
    {
        $validated = $request->validate([
            'registration_number' => 'required|string|max:20|unique:company_vehicles',
            'make' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'original_cost' => 'required|numeric|min:0',
            'acquisition_date' => 'required|date',
            'employee_id' => 'nullable|exists:employees,id',
            'private_use_percentage' => 'required|integer|min:0|max:100',
            'fuel_card_number' => 'nullable|string|max:50',
            'monthly_fuel_limit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validated['employee_id']) {
            $validated['status'] = 'assigned';
            $validated['assignment_date'] = now();
        } else {
            $validated['status'] = 'available';
        }

        $vehicle = CompanyVehicle::create($validated);
        $vehicle->calculateTaxableBenefit();

        return redirect()->route('allowances.vehicles')
            ->with('success', 'Vehicle added successfully.');
    }

    /**
     * Show vehicle details
     */
    public function showVehicle(CompanyVehicle $vehicle)
    {
        $vehicle->load('employee');

        return view('allowances.vehicles.show', compact('vehicle'));
    }

    /**
     * Assign vehicle to employee
     */
    public function assignVehicle(Request $request, CompanyVehicle $vehicle)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'private_use_percentage' => 'required|integer|min:0|max:100',
        ]);

        $vehicle->update([
            'employee_id' => $validated['employee_id'],
            'private_use_percentage' => $validated['private_use_percentage'],
            'status' => 'assigned',
            'assignment_date' => now(),
        ]);

        $vehicle->calculateTaxableBenefit();

        return back()->with('success', 'Vehicle assigned successfully.');
    }

    /**
     * Unassign vehicle
     */
    public function unassignVehicle(CompanyVehicle $vehicle)
    {
        $vehicle->update([
            'employee_id' => null,
            'status' => 'available',
            'assignment_date' => null,
            'annual_taxable_benefit' => 0,
            'monthly_taxable_benefit' => 0,
        ]);

        return back()->with('success', 'Vehicle unassigned.');
    }

    /**
     * Employee allowances summary
     */
    public function employeeAllowances(Employee $employee)
    {
        $allowances = EmployeeAllowance::with('allowanceType')
            ->where('employee_id', $employee->id)
            ->get();

        $activeAllowances = $allowances->where('status', 'active');
        $monthlyTotal = $activeAllowances->sum('monthly_amount');
        $taxableTotal = $activeAllowances->sum('taxable_amount');

        $vehicle = CompanyVehicle::where('employee_id', $employee->id)
            ->where('status', 'assigned')
            ->first();

        return view('allowances.employee', compact('employee', 'allowances', 'monthlyTotal', 'taxableTotal', 'vehicle'));
    }
}
