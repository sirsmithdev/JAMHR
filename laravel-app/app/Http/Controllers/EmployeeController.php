<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('employee')
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });

        $users = $query->orderBy('name')->paginate(15);

        return view('employees.index', compact('users'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,manager,employee,kiosk',
            'job_title' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'trn_number' => 'nullable|string|max:20',
            'nis_number' => 'nullable|string|max:20',
            'start_date' => 'nullable|date',
            'salary_annual' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        // Create user account
        $user = User::create([
            'name' => "{$validated['first_name']} {$validated['last_name']}",
            'email' => $validated['email'],
            'password' => Hash::make('password'), // Default password
            'role' => $validated['role'],
        ]);

        // Create employee record
        Employee::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'job_title' => $validated['job_title'],
            'department' => $validated['department'],
            'trn_number' => $validated['trn_number'],
            'nis_number' => $validated['nis_number'],
            'start_date' => $validated['start_date'],
            'salary_annual' => $validated['salary_annual'],
            'hourly_rate' => $validated['hourly_rate'],
            'pin' => str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'timeEntries', 'leaveRequests', 'payrolls']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load('user');
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($employee->user_id)],
            'role' => 'required|in:admin,manager,employee,kiosk',
            'job_title' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
            'trn_number' => 'nullable|string|max:20',
            'nis_number' => 'nullable|string|max:20',
            'start_date' => 'nullable|date',
            'salary_annual' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        // Update user
        $employee->user->update([
            'name' => "{$validated['first_name']} {$validated['last_name']}",
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Update employee
        $employee->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'job_title' => $validated['job_title'],
            'department' => $validated['department'],
            'trn_number' => $validated['trn_number'],
            'nis_number' => $validated['nis_number'],
            'start_date' => $validated['start_date'],
            'salary_annual' => $validated['salary_annual'],
            'hourly_rate' => $validated['hourly_rate'],
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->delete();
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
