<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(
            fn (Request $request) => $request->user()?->dashboardUrl() ?? route('home')
        );
    }
}
