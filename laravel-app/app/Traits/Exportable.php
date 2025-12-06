<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait Exportable
{
    /**
     * Export collection to CSV
     */
    public function exportToCsv(Collection $data, array $columns, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($data, $columns) {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write headers
            fputcsv($handle, array_values($columns));

            // Write data
            foreach ($data as $row) {
                $rowData = [];
                foreach (array_keys($columns) as $key) {
                    $value = data_get($row, $key);

                    // Format dates
                    if ($value instanceof \Carbon\Carbon) {
                        $value = $value->format('Y-m-d H:i:s');
                    }

                    // Handle arrays/objects
                    if (is_array($value) || is_object($value)) {
                        $value = json_encode($value);
                    }

                    $rowData[] = $value;
                }
                fputcsv($handle, $rowData);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export collection to JSON
     */
    public function exportToJson(Collection $data, string $filename): Response
    {
        return response($data->toJson(JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Get export columns for a model
     */
    protected function getExportColumns(string $type): array
    {
        return match($type) {
            'employees' => [
                'id' => 'ID',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'job_title' => 'Job Title',
                'department' => 'Department',
                'start_date' => 'Start Date',
                'salary_annual' => 'Annual Salary',
                'trn_number' => 'TRN',
                'nis_number' => 'NIS Number',
            ],
            'payroll' => [
                'id' => 'ID',
                'employee.full_name' => 'Employee',
                'period_start' => 'Period Start',
                'period_end' => 'Period End',
                'gross_pay' => 'Gross Pay',
                'nis_employee' => 'NIS (Employee)',
                'nht_employee' => 'NHT (Employee)',
                'education_tax' => 'Education Tax',
                'paye' => 'PAYE',
                'net_pay' => 'Net Pay',
                'status' => 'Status',
            ],
            'leave' => [
                'id' => 'ID',
                'employee.full_name' => 'Employee',
                'type' => 'Leave Type',
                'start_date' => 'Start Date',
                'end_date' => 'End Date',
                'days_requested' => 'Days',
                'status' => 'Status',
                'created_at' => 'Requested On',
            ],
            'time' => [
                'id' => 'ID',
                'employee.full_name' => 'Employee',
                'date' => 'Date',
                'clock_in' => 'Clock In',
                'clock_out' => 'Clock Out',
                'total_hours' => 'Hours',
                'status' => 'Status',
            ],
            default => [],
        };
    }
}
