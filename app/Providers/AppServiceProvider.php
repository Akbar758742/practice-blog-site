<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Comment;
use App\Models\Post;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Comment::observe(CommentObserver::class);
        Post::observe(PostObserver::class);

        //redirect if authenticated
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('admin.dashboard');
        });
        Authenticate::redirectUsing(function () {
            Session::flash('fail', 'You need to login firstt');
            return route('admin.login');
        });
    }
}
