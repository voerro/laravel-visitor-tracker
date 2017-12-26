<?php

namespace Voerro\Laravel\VisitorTracker\Facades;

use Illuminate\Support\Facades\Facade;

class Tracker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-visitor-tracker';
    }
}
