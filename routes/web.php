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
    Route::middleware([])->group(function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/login', 'loginForm')->name('login');
            Route::get('/forget-password', 'forgetPassword')->name('forgetPassword');
            // Route::post('/login', 'postLogin')->name('postLogin');
            // Route::get('/register', 'register')->name('register');
            // Route::post('/register', 'postRegister')->name('postRegister');
            // Route::get('/logout', 'logout')->name('logout');
        });
    });

    Route::middleware([])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'AdminDashboard')->name('dashboard');
        });
    });
});
