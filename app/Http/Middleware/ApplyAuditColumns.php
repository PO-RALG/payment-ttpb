<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\Setup\Concerns\AppliesAuditColumns;
use Closure;
use Illuminate\Http\Request;

class ApplyAuditColumns
{
    use AppliesAuditColumns;

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
}
