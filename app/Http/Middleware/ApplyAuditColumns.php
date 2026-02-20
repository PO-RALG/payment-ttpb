<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApplyAuditColumns
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            if ($request->isMethod('post')) {
                $request->merge($this->applyAuditColumns($request->all()));
            } elseif ($request->isMethod('put') || $request->isMethod('patch')) {
                $request->merge($this->applyAuditColumns($request->all(), true));
            }
        }

        return $next($request);
    }

    private function applyAuditColumns(array $attributes, bool $isUpdate = false): array
    {
        $userId = auth()->id();

        if (! $userId) {
            return $attributes;
        }

        if (! $isUpdate && empty($attributes['created_by'])) {
            $attributes['created_by'] = $userId;
        }

        if (empty($attributes['updated_by'])) {
            $attributes['updated_by'] = $userId;
        }

        return $attributes;
    }
}
