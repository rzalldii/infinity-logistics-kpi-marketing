<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Rate;
use App\Models\Shipper;
use App\Models\Activity;
use App\Models\User;
use App\Observers\RateObserver;
use App\Observers\ShipperObserver;
use App\Observers\ActivityObserver;
use App\Observers\UserObserver;

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
        Rate::observe(RateObserver::class);
        Shipper::observe(ShipperObserver::class);
        Activity::observe(ActivityObserver::class);
        User::observe(UserObserver::class);
    }
}
