<?php

use App\Http\Controllers\MobileApiController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [MobileApiController::class, 'login']);

Route::post('/attendance/biometric/check-in', [AttendanceController::class, 'biometricCheckIn']);

Route::middleware('auth.api')->group(function () {
    Route::post('/logout', [MobileApiController::class, 'logout']);
    Route::get('/me', [MobileApiController::class, 'me']);
    Route::post('/attendance/check-in', [MobileApiController::class, 'checkIn']);
    Route::post('/attendance/check-out', [MobileApiController::class, 'checkOut']);
    Route::get('/attendance', [MobileApiController::class, 'attendance']);
    Route::get('/leaves', [MobileApiController::class, 'leaves']);
    Route::post('/leaves', [MobileApiController::class, 'storeLeave']);
    Route::get('/notifications', [MobileApiController::class, 'notifications']);
    Route::get('/calendar/events', [MobileApiController::class, 'calendarEvents']);
});
