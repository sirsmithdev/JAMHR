<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::getAll();

        return view('settings.index', [
            'settings' => $settings,
            'activeTab' => 'company',
        ]);
    }

    /**
     * Show company settings
     */
    public function company()
    {
        $settings = Setting::company();
        $parishes = $this->getJamaicanParishes();

        return view('settings.company', [
            'settings' => $settings,
            'parishes' => $parishes,
            'activeTab' => 'company',
        ]);
    }

    /**
     * Update company settings
     */
    public function updateCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'registration_number' => 'nullable|string|max:100',
            'tax_registration_number' => 'nullable|string|max:50',
            'nis_number' => 'nullable|string|max:50',
            'nht_number' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'parish' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'fiscal_year_start' => 'nullable|string|max:10',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public');
            $validated['logo'] = $logoPath;

            // Delete old logo if exists
            $oldLogo = Setting::get('company.logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
        }

        Setting::setMany('company', $validated);

        return redirect()->route('settings.company')
            ->with('success', 'Company settings updated successfully.');
    }

    /**
     * Show payroll settings
     */
    public function payroll()
    {
        $settings = Setting::payroll();

        return view('settings.payroll', [
            'settings' => $settings,
            'activeTab' => 'payroll',
        ]);
    }

    /**
     * Update payroll settings
     */
    public function updatePayroll(Request $request)
    {
        $validated = $request->validate([
            'pay_frequency' => ['required', Rule::in(['weekly', 'bi-weekly', 'semi-monthly', 'monthly'])],
            'pay_day' => 'required|string|max:20',
            'currency' => 'required|string|max:3',
            'currency_symbol' => 'required|string|max:5',
            'overtime_rate' => 'required|numeric|min:1|max:5',
            'double_time_rate' => 'required|numeric|min:1|max:5',
            'standard_hours_per_week' => 'required|integer|min:20|max:60',
            'paye_threshold_annual' => 'required|numeric|min:0',
            'paye_rate' => 'required|numeric|min:0|max:1',
            'paye_rate_higher' => 'required|numeric|min:0|max:1',
            'paye_higher_threshold' => 'required|numeric|min:0',
            'nis_rate_employee' => 'required|numeric|min:0|max:1',
            'nis_rate_employer' => 'required|numeric|min:0|max:1',
            'nis_ceiling_weekly' => 'required|numeric|min:0',
            'nht_rate_employee' => 'required|numeric|min:0|max:1',
            'nht_rate_employer' => 'required|numeric|min:0|max:1',
            'education_tax_rate' => 'required|numeric|min:0|max:1',
            'heart_rate' => 'required|numeric|min:0|max:1',
            'auto_process_payroll' => 'nullable|boolean',
        ]);

        $validated['auto_process_payroll'] = $request->boolean('auto_process_payroll') ? 'true' : 'false';

        Setting::setMany('payroll', $validated);

        return redirect()->route('settings.payroll')
            ->with('success', 'Payroll settings updated successfully.');
    }

    /**
     * Show leave settings
     */
    public function leave()
    {
        $settings = Setting::leave();

        return view('settings.leave', [
            'settings' => $settings,
            'activeTab' => 'leave',
        ]);
    }

    /**
     * Update leave settings
     */
    public function updateLeave(Request $request)
    {
        $validated = $request->validate([
            'annual_leave_days' => 'required|integer|min:0|max:60',
            'sick_leave_days' => 'required|integer|min:0|max:60',
            'maternity_leave_weeks' => 'required|integer|min:0|max:26',
            'paternity_leave_days' => 'required|integer|min:0|max:30',
            'bereavement_leave_days' => 'required|integer|min:0|max:14',
            'accrual_method' => ['required', Rule::in(['annual', 'monthly', 'bi-weekly'])],
            'carry_over_enabled' => 'nullable|boolean',
            'carry_over_max_days' => 'required|integer|min:0|max:30',
            'carry_over_expiry_months' => 'required|integer|min:0|max:12',
            'require_approval' => 'nullable|boolean',
            'min_notice_days' => 'required|integer|min:0|max:30',
            'probation_leave_eligible' => 'nullable|boolean',
            'probation_period_months' => 'required|integer|min:0|max:12',
        ]);

        $validated['carry_over_enabled'] = $request->boolean('carry_over_enabled') ? 'true' : 'false';
        $validated['require_approval'] = $request->boolean('require_approval') ? 'true' : 'false';
        $validated['probation_leave_eligible'] = $request->boolean('probation_leave_eligible') ? 'true' : 'false';

        Setting::setMany('leave', $validated);

        return redirect()->route('settings.leave')
            ->with('success', 'Leave settings updated successfully.');
    }

    /**
     * Show notification settings
     */
    public function notifications()
    {
        $settings = Setting::notifications();

        return view('settings.notifications', [
            'settings' => $settings,
            'activeTab' => 'notifications',
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_enabled' => 'nullable|boolean',
            'leave_request_notify' => 'nullable|boolean',
            'leave_approval_notify' => 'nullable|boolean',
            'payroll_notify' => 'nullable|boolean',
            'loan_notify' => 'nullable|boolean',
            'appraisal_notify' => 'nullable|boolean',
            'birthday_notify' => 'nullable|boolean',
            'anniversary_notify' => 'nullable|boolean',
            'compliance_remind_days' => 'required|integer|min:1|max:30',
            'digest_frequency' => ['required', Rule::in(['none', 'daily', 'weekly'])],
        ]);

        $booleanFields = [
            'email_enabled', 'leave_request_notify', 'leave_approval_notify',
            'payroll_notify', 'loan_notify', 'appraisal_notify',
            'birthday_notify', 'anniversary_notify',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field) ? 'true' : 'false';
        }

        Setting::setMany('notifications', $validated);

        return redirect()->route('settings.notifications')
            ->with('success', 'Notification settings updated successfully.');
    }

    /**
     * Show system settings
     */
    public function system()
    {
        $settings = Setting::system();
        $timezones = timezone_identifiers_list();

        return view('settings.system', [
            'settings' => $settings,
            'timezones' => $timezones,
            'activeTab' => 'system',
        ]);
    }

    /**
     * Update system settings
     */
    public function updateSystem(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'required|timezone',
            'date_format' => ['required', Rule::in(['d/m/Y', 'm/d/Y', 'Y-m-d', 'd-m-Y', 'd M Y'])],
            'time_format' => ['required', Rule::in(['H:i', 'h:i A', 'H:i:s'])],
            'week_starts_on' => ['required', Rule::in(['sunday', 'monday'])],
            'session_timeout_minutes' => 'required|integer|min:5|max:480',
            'password_min_length' => 'required|integer|min:6|max:32',
            'password_require_special' => 'nullable|boolean',
            'two_factor_enabled' => 'nullable|boolean',
            'audit_log_enabled' => 'nullable|boolean',
            'audit_log_retention_days' => 'required|integer|min:30|max:1825',
            'backup_enabled' => 'nullable|boolean',
            'backup_frequency' => ['required', Rule::in(['daily', 'weekly'])],
            'maintenance_mode' => 'nullable|boolean',
        ]);

        $booleanFields = [
            'password_require_special', 'two_factor_enabled', 'audit_log_enabled',
            'backup_enabled', 'maintenance_mode',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field) ? 'true' : 'false';
        }

        Setting::setMany('system', $validated);

        return redirect()->route('settings.system')
            ->with('success', 'System settings updated successfully.');
    }

    /**
     * Show kiosk settings
     */
    public function kiosk()
    {
        $settings = Setting::getGroup('kiosk');

        // Set defaults if not configured
        $defaults = [
            'require_photo' => true,
            'photo_quality' => 'medium',
            'work_start_hour' => 9,
            'overtime_threshold' => 8,
            'allow_multiple_clockins' => false,
            'show_employee_photo' => true,
            'title' => 'Employee Time Clock',
            'subtitle' => 'Enter your PIN to clock in or out',
            'enable_breaks' => true,
            'camera_facing' => 'user',
            'photo_retention_days' => 90,
            'allow_manual_entry' => true,
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($settings[$key])) {
                $settings[$key] = $value;
            }
        }

        return view('settings.kiosk', [
            'settings' => $settings,
            'activeTab' => 'kiosk',
        ]);
    }

    /**
     * Update kiosk settings
     */
    public function updateKiosk(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'subtitle' => 'nullable|string|max:200',
            'require_photo' => 'nullable|boolean',
            'photo_quality' => ['required', Rule::in(['low', 'medium', 'high'])],
            'camera_facing' => ['required', Rule::in(['user', 'environment'])],
            'work_start_hour' => 'required|integer|min:0|max:23',
            'overtime_threshold' => 'required|numeric|min:1|max:24',
            'allow_multiple_clockins' => 'nullable|boolean',
            'enable_breaks' => 'nullable|boolean',
            'show_employee_photo' => 'nullable|boolean',
            'photo_retention_days' => 'required|integer|min:7|max:365',
            'allow_manual_entry' => 'nullable|boolean',
        ]);

        $booleanFields = [
            'require_photo', 'allow_multiple_clockins', 'enable_breaks',
            'show_employee_photo', 'allow_manual_entry',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->boolean($field) ? 'true' : 'false';
        }

        Setting::setMany('kiosk', $validated);

        return redirect()->route('settings.kiosk')
            ->with('success', 'Kiosk settings updated successfully.');
    }

    /**
     * Get Jamaican parishes
     */
    private function getJamaicanParishes(): array
    {
        return [
            'Kingston',
            'St. Andrew',
            'St. Thomas',
            'Portland',
            'St. Mary',
            'St. Ann',
            'Trelawny',
            'St. James',
            'Hanover',
            'Westmoreland',
            'St. Elizabeth',
            'Manchester',
            'Clarendon',
            'St. Catherine',
        ];
    }

    /**
     * Clear settings cache
     */
    public function clearCache()
    {
        Setting::clearCache();

        return redirect()->back()
            ->with('success', 'Settings cache cleared successfully.');
    }

    /**
     * Export settings
     */
    public function export()
    {
        $settings = Setting::getAll();
        $json = json_encode($settings, JSON_PRETTY_PRINT);

        return response($json)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="jamhr-settings-' . date('Y-m-d') . '.json"');
    }

    /**
     * Import settings
     */
    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json|max:1024',
        ]);

        $content = file_get_contents($request->file('settings_file')->getRealPath());
        $settings = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()
                ->with('error', 'Invalid JSON file.');
        }

        foreach ($settings as $group => $values) {
            if (is_array($values)) {
                Setting::setMany($group, $values);
            }
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings imported successfully.');
    }
}
