<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\V1\HealthAPIController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthAPIController::class);
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('users', [AuthController::class, 'storeUser']);
        Route::post('users/{user}/deactivate', [AuthController::class, 'deactivateUser']);
        Route::post('users/{user}/reset-password', [AuthController::class, 'resetUserPassword']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('phone-otp/request', [AuthController::class, 'requestPhoneOtp']);
        Route::post('phone-otp/verify', [AuthController::class, 'verifyPhoneOtp']);
        Route::post('email-otp/request', [AuthController::class, 'requestEmailOtp']);
        Route::post('email-otp/verify', [AuthController::class, 'verifyEmailOtp']);
    });
});

Route::middleware(['auth:sanctum', 'audit.columns'])->group(function () {
    Route::resource('setup/roles', App\Http\Controllers\API\Setup\RoleAPIController::class)
        ->except(['create', 'edit'])
        ->names([
            'index' => 'setup.roles.index',
            'store' => 'setup.roles.store',
            'show' => 'setup.roles.show',
            'update' => 'setup.roles.update',
            'destroy' => 'setup.roles.destroy'
        ]);

    Route::resource('setup/nationalities', App\Http\Controllers\API\Setup\NationalityAPIController::class)
        ->except(['create', 'edit'])
        ->names([
            'index' => 'setup.nationalities.index',
            'store' => 'setup.nationalities.store',
            'show' => 'setup.nationalities.show',
            'update' => 'setup.nationalities.update',
            'destroy' => 'setup.nationalities.destroy'
        ]);

    Route::resource('setup/permissions', App\Http\Controllers\API\Setup\PermissionAPIController::class)
        ->except(['create', 'edit'])
        ->names([
            'index' => 'setup.permissions.index',
            'store' => 'setup.permissions.store',
            'show' => 'setup.permissions.show',
            'update' => 'setup.permissions.update',
            'destroy' => 'setup.permissions.destroy'
        ]);

    Route::resource('setup/role-permissions', App\Http\Controllers\API\Setup\RolePermissionAPIController::class)
        ->except(['create', 'edit'])
        ->names([
            'index' => 'setup.rolePermissions.index',
            'store' => 'setup.rolePermissions.store',
            'show' => 'setup.rolePermissions.show',
            'update' => 'setup.rolePermissions.update',
            'destroy' => 'setup.rolePermissions.destroy'
        ]);
    Route::post('setup/roles/assign-permissions', [\App\Http\Controllers\API\Setup\RolePermissionAPIController::class, 'assignPermissions']);

    Route::resource('designations', \App\Http\Controllers\API\Setup\DesignationAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('admin-hierarchies', \App\Http\Controllers\API\Setup\AdminHierarchyAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('admin-hierarchy-level-sections', \App\Http\Controllers\API\Setup\AdminHierarchyLevelSectionAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('admin-hierarchy-levels', \App\Http\Controllers\API\Setup\AdminHierarchyLevelAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('admin-hierarchy-sections', \App\Http\Controllers\API\Setup\AdminHierarchySectionAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('genders', \App\Http\Controllers\API\Setup\GenderAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('identity-types', \App\Http\Controllers\API\Setup\IdentityTypeAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('institutions', \App\Http\Controllers\API\Setup\InstitutionAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('licence-categories', \App\Http\Controllers\API\Setup\LicenceCategoryAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('organization-types', \App\Http\Controllers\API\Setup\OrganizationTypeAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('organization-units', \App\Http\Controllers\API\Setup\OrganizationUnitAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('registration-types', \App\Http\Controllers\API\Setup\RegistrationTypeAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('user-identifiers', \App\Http\Controllers\API\Setup\UserIdentifierAPIController::class)
        ->except(['create', 'edit']);

    Route::resource('user-roles', \App\Http\Controllers\API\Setup\UserRoleAPIController::class)
        ->except(['create', 'edit']);

        Route::resource('nationalities', App\Http\Controllers\API\Setup\NationalityAPIController::class)
            ->except(['create', 'edit']);
});
