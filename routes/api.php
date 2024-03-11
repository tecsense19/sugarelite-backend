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
    Route::post('/chat/send', [SugareliteController::class, 'sendMessage']);
    Route::get('/chat/list', [SugareliteController::class, 'messageList']);

    ///profileList
    Route::get('/profile/list', [SugareliteController::class, 'profileList']);

    //friendLlist
    Route::post('/friends', [SugareliteController::class, 'friend_list']);
    Route::post('/profile/friends', [SugareliteController::class, 'friend_profile_list']);
    
    Route::post('/privateimages/access', [SugareliteController::class, 'private_album']);
    
    
    
    Route::post('/newsletter', [NewslettersController::class, 'newsletter']);

    Route::post('/forgot/password', [SugareliteController::class, 'forgotPassword']);

    Route::post('/create/subscription', [StripeController::class, 'createSubscription']);
    Route::post('/start/stop/subscription', [StripeController::class, 'startStopSubscription']);
    Route::post('/cancel/subscription', [StripeController::class, 'cancelSubscription']);
    Route::post('/upgrade/downgrade/subscription', [StripeController::class, 'upgradeDowngrade']);
});

