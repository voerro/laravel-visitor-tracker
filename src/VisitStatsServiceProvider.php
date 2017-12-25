<?php

namespace Voerro\VisitStats;

use Illuminate\Support\ServiceProvider;

class VisitStatsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(Tracker::class, function () {
        //     return new Tracker();
        // });

        $this->app->alias(Tracker::class, 'laravel-visit-stats');
    }
}
