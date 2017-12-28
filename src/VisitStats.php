<?php

namespace Voerro\Laravel\VisitorTracker;

use Illuminate\Support\Facades\Route;

class VisitStats
{
    public static function routes()
    {
        Route::get('/statistics', '\Voerro\Laravel\VisitorTracker\Controllers\StatisticsController@test');
    }
}
