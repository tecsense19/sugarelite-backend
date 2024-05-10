<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\SugareliteController;
use App\Http\Controllers\Api\V1\NewslettersController;
use App\Http\Controllers\Api\V1\StripeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'V1'], function () {
    Route::post('/profile/register', [SugareliteController::class, 'register']);
    Route::post('/checkUser', [SugareliteController::class, 'checkUser']);
    Route::post('/login', [SugareliteController::class, 'login']);
    Route::post('/logout', [SugareliteController::class, 'logout']);
    Route::post('/chat/send', [SugareliteController::class, 'sendMessage']);
    Route::get('/chat/list', [SugareliteController::class, 'messageList']);

    ///ProfileList
    Route::get('/profile/list', [SugareliteController::class, 'profileList']);

    //FriendLlist
    // Route::post('/friends', [SugareliteController::class, 'friend_list']);
    Route::post('/friendsnew', [SugareliteController::class, 'friend_list_new']);
    Route::post('/profile/friends', [SugareliteController::class, 'friend_profile_list']);

    //Access
    Route::post('/privateimages/access', [SugareliteController::class, 'private_album']);
    //private_album_decline
    Route::post('/privateimages/access/decline', [SugareliteController::class, 'privateAlbumAcceptReject']);
    //Push
    Route::post('/push/privatealbum', [SugareliteController::class, 'push_notifications_private_album']);
    Route::get('/push/friendrequest', [SugareliteController::class, 'push_notifications_friend_request']);
    Route::post('/push/messages', [SugareliteController::class, 'push_notifications_message']);
    

    //Newsletter
    Route::post('/newsletter', [NewslettersController::class, 'newsletter']);

    //Password
    Route::post('/forgot/password', [SugareliteController::class, 'forgotPassword']);

    //blockedUser
    Route::post('/block/user', [SugareliteController::class, 'blockUser']);
    Route::post('/report/user', [SugareliteController::class, 'reportUser']);

    //contactUs
    Route::post('/contactus', [SugareliteController::class, 'contactUs']);

    //ReadMessage
    Route::post('/readmessage', [SugareliteController::class, 'readMessage']);
    Route::post('/readprivatealbum', [SugareliteController::class, 'readPrivateAlbumAccess']);

    Route::post('/readfriend_request', [SugareliteController::class, 'readFriendRequestNotifiaction']);

    Route::post('/otp', [SugareliteController::class, 'MobileEmailOtp']); 
    Route::post('/verifyotp', [SugareliteController::class, 'MobileEmailVerifyOtp']); 

    Route::post('/elitesupport', [SugareliteController::class, 'EliteSupport']); 
    Route::post('/getelitesupport', [SugareliteController::class, 'EliteSupportData']); 
    
    Route::post('/verifyidentity', [SugareliteController::class, 'IdentityVerification']); 

    Route::post('/create/subscription', [StripeController::class, 'createSubscription']);
    Route::post('/start/stop/subscription', [StripeController::class, 'startStopSubscription']);
    Route::post('/cancel/subscription', [StripeController::class, 'cancelSubscription']);
    Route::post('/upgrade/downgrade/subscription', [StripeController::class, 'upgradeDowngrade']);
    Route::post('/create/webhook', [StripeController::class, 'createWebhook']);
    Route::post('/create/test/clock', [StripeController::class, 'createTestClock']);

   
});

