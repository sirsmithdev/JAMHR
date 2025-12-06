<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'year',
        'is_observed',
        'observed_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_observed' => 'boolean',
            'observed_date' => 'date',
        ];
    }

    public function getActualDateAttribute()
    {
        return $this->observed_date ?? $this->date;
    }

    public static function isHoliday($date): bool
    {
        $date = is_string($date) ? \Carbon\Carbon::parse($date) : $date;

        return self::where(function ($query) use ($date) {
            $query->whereDate('date', $date)
                ->orWhereDate('observed_date', $date);
        })->exists();
    }

    public static function getHolidaysInRange($startDate, $endDate): \Illuminate\Database\Eloquent\Collection
    {
        return self::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate])
                ->orWhereBetween('observed_date', [$startDate, $endDate]);
        })->get();
    }

    /**
     * Seed Jamaica public holidays for a given year
     */
    public static function seedJamaicaHolidays(int $year): void
    {
        $holidays = [
            ['name' => "New Year's Day", 'date' => "$year-01-01"],
            ['name' => 'Ash Wednesday', 'date' => null], // Variable
            ['name' => 'Good Friday', 'date' => null], // Variable
            ['name' => 'Easter Monday', 'date' => null], // Variable
            ['name' => 'Labour Day', 'date' => "$year-05-23"],
            ['name' => 'Emancipation Day', 'date' => "$year-08-01"],
            ['name' => 'Independence Day', 'date' => "$year-08-06"],
            ['name' => 'National Heroes Day', 'date' => null], // Third Monday in October
            ['name' => 'Christmas Day', 'date' => "$year-12-25"],
            ['name' => 'Boxing Day', 'date' => "$year-12-26"],
        ];

        // Calculate Easter-based holidays
        $easter = \Carbon\Carbon::createFromTimestamp(easter_date($year));
        $ashWednesday = $easter->copy()->subDays(46);
        $goodFriday = $easter->copy()->subDays(2);
        $easterMonday = $easter->copy()->addDay();

        // National Heroes Day: Third Monday in October
        $october1 = \Carbon\Carbon::create($year, 10, 1);
        $heroesDay = $october1->nthOfMonth(3, \Carbon\Carbon::MONDAY);

        foreach ($holidays as &$holiday) {
            if ($holiday['name'] === 'Ash Wednesday') {
                $holiday['date'] = $ashWednesday->toDateString();
            } elseif ($holiday['name'] === 'Good Friday') {
                $holiday['date'] = $goodFriday->toDateString();
            } elseif ($holiday['name'] === 'Easter Monday') {
                $holiday['date'] = $easterMonday->toDateString();
            } elseif ($holiday['name'] === 'National Heroes Day') {
                $holiday['date'] = $heroesDay->toDateString();
            }

            if ($holiday['date']) {
                self::updateOrCreate(
                    ['name' => $holiday['name'], 'year' => $year],
                    [
                        'date' => $holiday['date'],
                        'is_observed' => false,
                        'observed_date' => null,
                    ]
                );
            }
        }
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year)->orderBy('date');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())->orderBy('date');
    }
}
