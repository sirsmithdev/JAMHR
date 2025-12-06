<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Searchable
{
    /**
     * Apply search filters to the query
     */
    public function scopeSearch(Builder $query, Request $request, array $searchableFields = []): Builder
    {
        // Text search across multiple fields
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search, $searchableFields) {
                foreach ($searchableFields as $field) {
                    if (str_contains($field, '.')) {
                        // Relationship search
                        [$relation, $column] = explode('.', $field);
                        $q->orWhereHas($relation, function ($query) use ($column, $search) {
                            $query->where($column, 'like', "%{$search}%");
                        });
                    } else {
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                }
            });
        }

        return $query;
    }

    /**
     * Apply filters to the query
     */
    public function scopeFilter(Builder $query, Request $request, array $filterableFields = []): Builder
    {
        foreach ($filterableFields as $field => $options) {
            $paramName = is_string($field) ? $field : $options;
            $column = is_array($options) ? ($options['column'] ?? $paramName) : $paramName;

            if ($request->filled($paramName)) {
                $value = $request->input($paramName);

                if (is_array($options) && isset($options['type'])) {
                    switch ($options['type']) {
                        case 'date_from':
                            $query->whereDate($column, '>=', $value);
                            break;
                        case 'date_to':
                            $query->whereDate($column, '<=', $value);
                            break;
                        case 'boolean':
                            $query->where($column, filter_var($value, FILTER_VALIDATE_BOOLEAN));
                            break;
                        case 'in':
                            $query->whereIn($column, (array) $value);
                            break;
                        case 'relation':
                            [$relation, $relationColumn] = explode('.', $options['relation']);
                            $query->whereHas($relation, function ($q) use ($relationColumn, $value) {
                                $q->where($relationColumn, $value);
                            });
                            break;
                        default:
                            $query->where($column, $value);
                    }
                } else {
                    $query->where($column, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Apply sorting to the query
     */
    public function scopeSortBy(Builder $query, Request $request, string $defaultSort = 'created_at', string $defaultDirection = 'desc'): Builder
    {
        $sort = $request->input('sort', $defaultSort);
        $direction = $request->input('direction', $defaultDirection);

        // Validate direction
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'desc';

        return $query->orderBy($sort, $direction);
    }
}
