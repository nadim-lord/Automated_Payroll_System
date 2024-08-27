<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\LeaveController;

//Layout Part
Route::get('dashboard', [LayoutController::class, 'dashboard']);

//Auth Part
Route::get('register',[AuthController::class, 'register']);
Route::post('create-user',[AuthController::class, 'createUser']);
Route::get('login',[AuthController::class, 'login']);
Route::post('user-login',[AuthController::class, 'userLogin']);

//User, Time & Leave Part
Route::middleware(['CheckLoggedIn'])->group(function () {
    Route::get('users',[UserController::class, 'allUsers']);
    Route::get('approve/{userId}', [UserController::class, 'approve']);
    Route::get('time', [TimeEntryController::class, 'index'])->name('time.index');
    Route::post('time/clock-in', [TimeEntryController::class, 'clockIn'])->name('time.clock-in');
    Route::post('time/clock-out', [TimeEntryController::class, 'clockOut'])->name('time.clock-out');
    Route::put('/time/{id}', [TimeEntryController::class, 'update'])->name('time.update');
    Route::delete('/time/{id}', [TimeEntryController::class, 'destroy'])->name('time.destroy');
    Route::get('leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::post('leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::put('/leaves/{id}', [LeaveController::class, 'update'])->name('leave.update');
    Route::delete('/leaves/{id}', [LeaveController::class, 'destroy'])->name('leave.destroy');
});