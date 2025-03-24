<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => UserController::class,], function () {
    Route::post('login', 'login')->withoutMiddleware('auth:sanctum');
    Route::post('forgot-password', 'forgotPassword')->withoutMiddleware('auth:sanctum');
    Route::post('otp-verify', 'otpVerify')->withoutMiddleware('auth:sanctum');
    Route::post('resend-otp', 'resendOtp')->withoutMiddleware('auth:sanctum');
    Route::post('create-new-password', 'createNewPassword')->middleware(['auth:sanctum','admin']);
    Route::put('update-profile', 'updateProfile')->middleware(['auth:sanctum','admin']);
    Route::get('profile', 'profile')->middleware(['auth:sanctum','admin']);
});