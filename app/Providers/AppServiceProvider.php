<?php

namespace App\Providers;

use App\Models\Punch;
use App\Observers\PunchObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Punch::observe(PunchObserver::class);

   
    }
}
