<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationTransactionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PodcastStoreController;
use App\Http\Controllers\ServiceBookController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SurvivorController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VolunteerController;
use App\Models\DonationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['controller' => UserController::class], function () {
    Route::post('register', 'register')->withoutMiddleware('auth:sanctum');
    Route::post('login', 'login')->withoutMiddleware('auth:sanctum');
    Route::post('forgot-password', 'forgotPassword')->withoutMiddleware('auth:sanctum');
    Route::post('otp-verify', 'otpVerify')->withoutMiddleware('auth:sanctum');
    Route::post('resend-otp', 'resendOtp')->withoutMiddleware('auth:sanctum');
    Route::post('create-new-password', 'createNewPassword')->middleware(['auth:sanctum','user']);
    Route::put('update-profile', 'updateProfile')->middleware(['auth:sanctum','user']);
    Route::get('profile', 'profile')->middleware(['auth:sanctum','user']);
});
Route::group(['controller'=>DashboardController::class],function(){
    Route::get('dashboard','dashboard')->middleware(['auth:sanctum','admin']);;
});
Route::group(['controller'=>PaymentController::class],function(){
    Route::post('create-payment-intent','createPaymentIntent');
    Route::post('create-paypal-payment-intent','createPaypalPaymentIntent');
});
Route::group(['controller'=>ContributorController::class],function(){
    Route::post('bit-contributor','BitContributor')->middleware('auth:sanctum','user');
    Route::get('get-contributor','getContributor')->middleware(['admin','auth:sanctum']);

    Route::get('auction-soltout','auctionSoltout');

    Route::get('contributor-details','contributorDetails')->middleware(['admin','auth:sanctum']);
    Route::get('single-contributor-auction','singleContributorAuction')->middleware(['admin','auth:sanctum']);
    Route::patch('contributor-status-change','contributorStatusChange')->middleware(['admin','auth:sanctum']);
});
Route::group(['controller'=>SurvivorController::class],function(){
    Route::post('donate-money','donateMoney');
    Route::post('collect-table','collectTable');
});
Route::group(['controller'=>VolunteerController::class],function(){
    Route::post('create-volunteer','createVolunteer');
    Route::get('get-volunteer','getVolunteer')->middleware(['admin','auth:sanctum']);
    Route::patch('volunteer-status','volunteerStatus')->middleware(['admin','auth:sanctum']);
});
Route::group(['controller'=>AuctionController::class],function(){
    Route::get('get-bit-auction','getBitAuction');
    Route::post('auction','auction');
    Route::get('get-auction','getAuction')->middleware(['admin','auth:sanctum']);
    Route::get('auction-details','auctionDetails')->middleware(['admin','auth:sanctum']);
    Route::put('update-auction','updateAuction')->middleware(['admin','auth:sanctum']);
    Route::put('assign-budget','asignBudget')->middleware(['admin','auth:sanctum']);
    Route::delete('delete-auction','deleteAuction')->middleware(['admin','auth:sanctum']);
});
Route::group(['controller'=>PodcastStoreController::class],function(){
    Route::get('get-podcast','getPodcast');
    Route::post('create-podcast','createPodCast')->middleware(['admin','auth:sanctum']);
    Route::put('update-podcast','updatePodCast')->middleware(['admin','auth:sanctum']);
    Route::get('details-podcast','detailsPodcast')->middleware(['admin','auth:sanctum']);
    Route::delete('delete-podcast','deletePodcast')->middleware(['admin','auth:sanctum']);
});
Route::group(['controller'=>TeamController::class],function(){
    Route::get('get-team','getTeam');
    Route::post('create-team','createTeam')->middleware(['admin','auth:sanctum']);
    Route::get('team','team')->middleware(['admin','auth:sanctum']);
    Route::put('update-team','updateTeam')->middleware(['admin','auth:sanctum']);
    Route::delete('delete-team','deleteTeam')->middleware(['admin','auth:sanctum']);
});
Route::group(['controller'=>ServiceBookController::class],function(){
    Route::post('create-book','createBook');
    Route::get('get-available-booking-time','getAvailableBookingTime');
    Route::get('get-book','getBook')->middleware(['admin','auth:sanctum']);
    Route::patch('book-status','bookStatus')->middleware(['admin','auth:sanctum']);
});
Route::controller(FaqController::class)->group(function () {
    Route::get('get-faqs', 'getFaqs');
    Route::post('create-faq', 'createFaq')->middleware(['auth:sanctum', 'admin']);
    Route::put('update-faq', 'updateFaq')->middleware(['auth:sanctum', 'admin']);
    Route::get('faq', 'Faq')->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete-faq', 'deleteFaq')->middleware(['auth:sanctum', 'admin']);
});
Route::group(['controller'=>SubscriberController::class],function(){
    Route::post('subscriber','subscriber');
    Route::get('get-subscriber','getSubscriber')->middleware(['admin','auth:sanctum']);;
});
Route::group(['controller'=>DonationTransactionController::class],function(){
    Route::get('donate-transations','getDonateTransation')->middleware(['admin','auth:sanctum']);;
});
Route::group(['middleware' => ['auth:sanctum','admin'], 'controller' => NotificationController::class], function () {
    Route::get('notifications', 'notifications');
    Route::post('notifications/read/{id}', 'markAsRead');
    Route::post('notifications-read-all','notificationReadAll');
});
