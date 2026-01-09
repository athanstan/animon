<?php

namespace App\Providers;

use App\Http\Integrations\Jikan\JikanConnector;
use App\Interfaces\JikanInterface;
use App\Services\Jikan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(JikanConnector::class, fn() => new JikanConnector());
        $this->app->bind(JikanInterface::class, Jikan::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
