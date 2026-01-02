<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Session;


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
