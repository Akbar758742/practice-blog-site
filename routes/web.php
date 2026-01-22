<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/', \App\Livewire\Frontend\Home::class)->name('home');
Route::get('/search', \App\Livewire\Frontend\SearchPosts::class)->name('search');
Route::get('/post/{slug}', \App\Livewire\Frontend\SinglePost::class)->name('post.single');
Route::get('/category/{slug}', \App\Livewire\Frontend\CategoryPosts::class)->name('category');
Route::view('/example-auth', 'example-auth');



/**
 * admin routes
 */

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest', 'preventBackHistory'])->group(function () {
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

    Route::middleware(['auth', 'preventBackHistory'])->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'AdminDashboard')->name('dashboard');
            Route::post('/logout', 'logoutHandler')->name('logout');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/profile/update', 'profilePicUpdate')->name('profilePic.update');
            Route::get('settings', 'generalSettings')->name('settings');
            Route::post('settings', 'generalSettingsUpdate')->name('settings.update');
            Route::get('/categories', \App\Livewire\Admin\Categories::class)->name('categories');
            Route::get('/tags', \App\Livewire\Admin\Tags::class)->name('tags');
            Route::prefix('posts')->name('posts.')->group(function () {
                Route::get('/', \App\Livewire\Admin\Posts::class)->name('index');
                Route::get('/create', \App\Livewire\Admin\CreatePost::class)->name('create');
                Route::get('/{id}/edit', \App\Livewire\Admin\EditPost::class)->name('edit');
            });
        });
    });
});
