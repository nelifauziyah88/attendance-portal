<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
        if (config('app.env') === 'local') {
            URL::forceRootUrl(config('app.url'));
        }

        Paginator::currentPathResolver(function () {
            return url(request()->path());
        });
        Gate::define('viewLogViewer', fn ($user = null) => $user !== null);
    }
}
