<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\HealthAPIController;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthAPIController::class);
});