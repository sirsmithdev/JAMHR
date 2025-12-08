<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Setting;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class KioskController extends Controller
{
    public function index()
    {
        $settings = $this->getKioskSettings();
        return view('kiosk.index', compact('settings'));
    }

    public function clockIn(Request $request)
    {
        $settings = $this->getKioskSettings();

        $rules = [
            'pin' => 'required|string|size:4',
        ];

        if ($settings['require_photo']) {
            $rules['photo'] = 'required|string';
        }

        $validated = $request->validate($rules);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        // Check if already clocked in today
        $existingEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingEntry && $existingEntry->clock_in && !$settings['allow_multiple_clockins']) {
            return response()->json([
                'success' => false,
                'message' => 'Already clocked in today.',
            ], 400);
        }

        // Handle photo upload
        $photoPath = null;
        if ($settings['require_photo'] && isset($validated['photo'])) {
            $photoPath = $this->storePhoto($validated['photo'], $employee->id, 'clock_in');
        }

        // Create or update time entry
        $now = now();
        $workStartHour = (int) ($settings['work_start_hour'] ?? 9);
        $status = $now->hour >= $workStartHour ? 'late' : 'on_time';

        $entryData = [
            'clock_in' => $now,
            'clock_in_photo' => $photoPath,
            'status' => $status,
            'ip_address' => $request->ip(),
            'device_info' => $request->userAgent(),
        ];

        if ($existingEntry) {
            $existingEntry->update($entryData);
        } else {
            TimeEntry::create(array_merge([
                'employee_id' => $employee->id,
                'date' => $today,
            ], $entryData));
        }

        return response()->json([
            'success' => true,
            'message' => "Clock In Successful for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
        ]);
    }

    public function clockOut(Request $request)
    {
        $settings = $this->getKioskSettings();

        $rules = [
            'pin' => 'required|string|size:4',
        ];

        if ($settings['require_photo']) {
            $rules['photo'] = 'required|string';
        }

        $validated = $request->validate($rules);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$timeEntry) {
            return response()->json([
                'success' => false,
                'message' => 'No active clock-in found for today.',
            ], 400);
        }

        // Handle photo upload
        $photoPath = null;
        if ($settings['require_photo'] && isset($validated['photo'])) {
            $photoPath = $this->storePhoto($validated['photo'], $employee->id, 'clock_out');
        }

        $now = now();
        $timeEntry->update([
            'clock_out' => $now,
            'clock_out_photo' => $photoPath,
        ]);

        // Calculate total hours
        $timeEntry->total_hours = $timeEntry->calculateTotalHours();

        // Check for overtime
        $overtimeThreshold = (float) ($settings['overtime_threshold'] ?? 8);
        if ($timeEntry->total_hours > $overtimeThreshold) {
            $timeEntry->status = 'overtime';
        }

        $timeEntry->save();

        return response()->json([
            'success' => true,
            'message' => "Clock Out Successful for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
            'total_hours' => $timeEntry->total_hours,
        ]);
    }

    public function startBreak(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->first();

        if (!$timeEntry) {
            return response()->json([
                'success' => false,
                'message' => 'Must be clocked in to start a break.',
            ], 400);
        }

        if ($timeEntry->break_start && !$timeEntry->break_end) {
            return response()->json([
                'success' => false,
                'message' => 'Break already in progress.',
            ], 400);
        }

        $now = now();
        $timeEntry->update(['break_start' => $now]);

        return response()->json([
            'success' => true,
            'message' => "Break Started for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
        ]);
    }

    public function endBreak(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN. Please try again.',
            ], 401);
        }

        $today = now()->toDateString();

        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNotNull('break_start')
            ->whereNull('break_end')
            ->first();

        if (!$timeEntry) {
            return response()->json([
                'success' => false,
                'message' => 'No active break found.',
            ], 400);
        }

        $now = now();
        $timeEntry->update(['break_end' => $now]);

        return response()->json([
            'success' => true,
            'message' => "Break Ended for {$employee->full_name} at " . $now->format('h:i A'),
            'employee' => $employee->full_name,
            'time' => $now->format('h:i A'),
        ]);
    }

    public function verifyPin(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|string|size:4',
        ]);

        $employee = Employee::where('pin', $validated['pin'])->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid PIN.',
            ], 401);
        }

        $today = now()->toDateString();
        $timeEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        return response()->json([
            'success' => true,
            'employee' => [
                'name' => $employee->full_name,
                'initials' => $employee->initials,
                'department' => $employee->department,
                'job_title' => $employee->job_title,
            ],
            'status' => [
                'clocked_in' => $timeEntry && $timeEntry->clock_in && !$timeEntry->clock_out,
                'on_break' => $timeEntry && $timeEntry->break_start && !$timeEntry->break_end,
                'clock_in_time' => $timeEntry?->clock_in?->format('h:i A'),
            ],
        ]);
    }

    protected function storePhoto(string $base64Image, int $employeeId, string $type): ?string
    {
        try {
            // Extract base64 data
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
                $extension = $matches[1];
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            } else {
                $extension = 'jpg';
            }

            $imageData = base64_decode($base64Image);

            if ($imageData === false) {
                return null;
            }

            // Generate unique filename
            $filename = sprintf(
                '%s/%s_%d_%s_%s.%s',
                now()->format('Y/m'),
                $type,
                $employeeId,
                now()->format('Y-m-d_H-i-s'),
                substr(md5(uniqid()), 0, 8),
                $extension
            );

            $path = 'kiosk-photos/' . $filename;

            // Store the image
            Storage::disk('public')->put($path, $imageData);

            return $path;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    protected function getKioskSettings(): array
    {
        return [
            'require_photo' => Setting::get('kiosk.require_photo', true),
            'photo_quality' => Setting::get('kiosk.photo_quality', 'medium'),
            'work_start_hour' => Setting::get('kiosk.work_start_hour', 9),
            'overtime_threshold' => Setting::get('kiosk.overtime_threshold', 8),
            'allow_multiple_clockins' => Setting::get('kiosk.allow_multiple_clockins', false),
            'show_employee_photo' => Setting::get('kiosk.show_employee_photo', true),
            'kiosk_title' => Setting::get('kiosk.title', 'Employee Time Clock'),
            'kiosk_subtitle' => Setting::get('kiosk.subtitle', 'Enter your PIN to clock in or out'),
            'enable_breaks' => Setting::get('kiosk.enable_breaks', true),
            'camera_facing' => Setting::get('kiosk.camera_facing', 'user'),
        ];
    }
}
