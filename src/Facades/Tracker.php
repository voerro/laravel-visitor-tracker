<?php

namespace Voerro\VisitStats\Facades;

use Illuminate\Support\Facades\Facade;

class Tracker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-visit-stats';
    }
}
