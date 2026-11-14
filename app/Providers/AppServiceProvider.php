<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production') || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        RedirectIfAuthenticated::redirectUsing(
            fn (Request $request) => $request->user()?->dashboardUrl() ?? route('home')
        );
    }
}
