<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ApiService::class, function () {
            return new ApiService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
