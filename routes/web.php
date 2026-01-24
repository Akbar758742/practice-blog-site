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
            // Open routes (all authenticated users)
            Route::get('/dashboard', 'AdminDashboard')->name('dashboard');
            Route::post('/logout', 'logoutHandler')->name('logout');
            Route::get('/profile', 'profileView')->name('profile');
            Route::post('/profile/update', 'profilePicUpdate')->name('profilePic.update');
        });

        // Categories - requires category.manage permission
        Route::middleware('permission:category.manage')->group(function () {
            Route::get('/categories', \App\Livewire\Admin\Categories::class)->name('categories');
        });

        // Tags - requires tag.manage permission
        Route::middleware('permission:tag.manage')->group(function () {
            Route::get('/tags', \App\Livewire\Admin\Tags::class)->name('tags');
        });

        // Posts - requires post permissions
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Posts::class)->name('index');
            Route::middleware('permission:post.create')->get('/create', \App\Livewire\Admin\CreatePost::class)->name('create');
            Route::middleware('permission:post.edit')->get('/{id}/edit', \App\Livewire\Admin\EditPost::class)->name('edit');
        });

        // Comments - requires comment permissions
        Route::middleware('permission:comment.view')->group(function () {
            Route::get('/comments', \App\Livewire\Admin\Comments::class)->name('comments');
        });

        // Pages - Admin Only
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Pages::class)->name('index');
            Route::get('/create', \App\Livewire\Admin\CreatePage::class)->name('create');
            Route::get('/{id}/edit', \App\Livewire\Admin\EditPage::class)->name('edit');
        });

        // User Management - requires user.manage permission
        Route::middleware('permission:user.manage')->group(function () {
            Route::get('/users', \App\Livewire\Admin\Users::class)->name('users');
        });

        // Roles & Permissions - requires role.manage permission
        Route::middleware('permission:role.manage')->group(function () {
            Route::get('/roles', \App\Livewire\Admin\Roles::class)->name('roles');
            Route::get('/permissions', \App\Livewire\Admin\Permissions::class)->name('permissions');
        });

        // Settings - requires settings.manage permission
        Route::middleware('permission:settings.manage')->group(function () {
            Route::controller(AdminController::class)->group(function () {
                Route::get('settings', 'generalSettings')->name('settings');
                Route::post('settings', 'generalSettingsUpdate')->name('settings.update');
            });
        });
    });
});
