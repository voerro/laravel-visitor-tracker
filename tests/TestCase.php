<?php

namespace Voerro\VisitStats\Test;

use Voerro\VisitStats\VisitStats\Facades\Tracker;
use Voerro\VisitStats\VisitStatsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return Voerro\VisitStats\VisitStatsServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [VisitStatsServiceProvider::class];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'VisitStats' => Tracker::class,
        ];
    }
}
