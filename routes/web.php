<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

/**
 * testing routes
 */
Route::view('/example-page', 'example-page');
Route::view('/example-auth', 'example-auth');



/**
 * admin routes
 */

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'loginForm')->name('login');
            Route::get('/forget-password', 'forgetPassword')->name('forgetPassword');
            Route::post('/login', 'loginHandler')->name('loginHandler');
            Route::post('/send-password-reset-link', 'sendPasswordResetLink')->name('sendPasswordResetLink');
            Route::get('/reset-password/{token}', 'resetPasswordForm')->name('resetPasswordForm');

            Route::post('/reset-password', 'resetPasswordHandler')->name('resetPasswordHandler');
            // Route::get('/register', 'register')->name('register');
            // Route::post('/register', 'postRegister')->name('postRegister');

        });
    });

    Route::middleware(['auth'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'AdminDashboard')->name('dashboard');
              Route::post('/logout', 'logoutHandler')->name('logout');
              Route::get('/profile','profileView')->name('profile');
              Route::post('/profile/update','profilePicUpdate')->name('profilePic.update');
        });
    });
});
