<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'registrationTypes', 'as' => 'registrationTypes.'],function(){
    Route::resource('/', App\Http\Controllers\RegistrationTypeController::class);
    Route::delete('/{id}', [App\Http\Controllers\RegistrationTypeController::class, 'destroy'])->name('destroy');
    Route::patch('/update/{id}', [App\Http\Controllers\RegistrationTypeController::class, 'update'])->name('update');
    Route::post('/{id}/restore', [App\Http\Controllers\RegistrationTypeController::class, 'restore'])->name('restore');
});
Route::group(['prefix' => 'roles', 'as' => 'roles.'],function(){
    Route::resource('/', App\Http\Controllers\RoleController::class);
    Route::delete('/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('destroy');
    Route::patch('/update/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('update');
    Route::post('/{id}/restore', [App\Http\Controllers\RoleController::class, 'restore'])->name('restore');
});