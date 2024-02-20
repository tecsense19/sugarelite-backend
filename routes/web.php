<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\ChatController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AdminAuthController::class, 'index'])->name('admin.login');
Route::post('/admin/custom/login', [AdminAuthController::class, 'Login'])->name('login.custom');
Route::get('/admin/signout', [AdminAuthController::class, 'signOut'])->name('signout');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.profile');
    Route::post('/add-profile', [ProfileController::class, 'profileregister'])->name('profile.add-profile');


    Route::get('/profiles', [ProfileController::class, 'profileindex'])->name('profile.profiles');
    Route::post('/list-profile', [ProfileController::class, 'profilelist'])->name('profile.list-profile');

    // update prodile
    Route::get('profile/edit/{id}', [ProfileController::class, 'profileedit'])->name('profile.edit-profile');
    Route::Post('/update-profile', [ProfileController::class, 'profileupdate'])->name('profile.update-profile');

    // delete profile
    Route::post('/profile/delete', [ProfileController::class, 'profiledelete'])->name('profile.delete-profile');
    // remove user image
    Route::post('/remove-user-images', [ProfileController::class, 'removeuserimage']);


});

Route::get('/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/chat/webhook/message', [ChatController::class, 'webhook']);
Route::get('/chat/list', [ChatController::class, 'messageList']);


// Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');