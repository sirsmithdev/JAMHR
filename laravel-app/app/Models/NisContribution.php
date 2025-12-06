<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NisContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_id',
        'contribution_date',
        'employee_contribution',
        'employer_contribution',
        'insurable_earnings',
        'weeks_credited',
        'ytd_weeks',
        'ytd_employee_contributions',
        'ytd_employer_contributions',
    ];

    protected function casts(): array
    {
        return [
            'contribution_date' => 'date',
            'employee_contribution' => 'decimal:2',
            'employer_contribution' => 'decimal:2',
            'insurable_earnings' => 'decimal:2',
            'ytd_employee_contributions' => 'decimal:2',
            'ytd_employer_contributions' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function getTotalContributionAttribute(): float
    {
        return $this->employee_contribution + $this->employer_contribution;
    }

    public function getYtdTotalContributionsAttribute(): float
    {
        return $this->ytd_employee_contributions + $this->ytd_employer_contributions;
    }

    public static function getEmployeeYtdSummary(int $employeeId, int $year): array
    {
        $contributions = self::where('employee_id', $employeeId)
            ->whereYear('contribution_date', $year)
            ->get();

        return [
            'total_weeks' => $contributions->sum('weeks_credited'),
            'employee_contributions' => $contributions->sum('employee_contribution'),
            'employer_contributions' => $contributions->sum('employer_contribution'),
            'total_contributions' => $contributions->sum('employee_contribution') + $contributions->sum('employer_contribution'),
            'insurable_earnings' => $contributions->sum('insurable_earnings'),
        ];
    }
}
