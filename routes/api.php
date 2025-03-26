<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SurvivorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => UserController::class], function () {
    Route::post('login', 'login')->withoutMiddleware('auth:sanctum');
    Route::post('forgot-password', 'forgotPassword')->withoutMiddleware('auth:sanctum');
    Route::post('otp-verify', 'otpVerify')->withoutMiddleware('auth:sanctum');
    Route::post('resend-otp', 'resendOtp')->withoutMiddleware('auth:sanctum');
    Route::post('create-new-password', 'createNewPassword')->middleware(['auth:sanctum','admin']);
    Route::put('update-profile', 'updateProfile')->middleware(['auth:sanctum','admin']);
    Route::get('profile', 'profile')->middleware(['auth:sanctum','admin']);
});
Route::group(['controller'=>PaymentController::class],function(){
    Route::post('create-payment-intent','createPaymentIntent');
});
Route::group(['controller'=>SurvivorController::class],function(){
    Route::post('support-survivor','supportSurvivor');
});
Route::group(['controller'=>VolunteerController::class],function(){
    Route::post('create-volunteer','createVolunteer');
    Route::get('get-volunteer','getVolunteer')->middleware(['admin','auth:sanctum']);
    Route::patch('volunteer-status','volunteerStatus')->middleware(['admin','auth:sanctum']);
});
