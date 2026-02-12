<?php

namespace App\Http\Controllers\API\Setup\Concerns;

use Illuminate\Support\Facades\Auth;

trait AppliesAuditColumns
{
    protected function applyAuditColumns(array $attributes, bool $isUpdate = false): array
    {
        $userId = Auth::id();

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
