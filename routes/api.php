<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\HealthAPIController;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthAPIController::class);
});



Route::resource('setup/roles', App\Http\Controllers\API\Setup\RoleAPIController::class)
    ->except(['create', 'edit'])
    ->names([
        'index' => 'setup.roles.index',
        'store' => 'setup.roles.store',
        'show' => 'setup.roles.show',
        'update' => 'setup.roles.update',
        'destroy' => 'setup.roles.destroy'
    ]);