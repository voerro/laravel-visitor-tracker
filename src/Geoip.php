<?php

namespace Voerro\Laravel\VisitorTracker;

use Voerro\Laravel\VisitorTracker\Geoip\Userinfo;
use Voerro\Laravel\VisitorTracker\Geoip\Freegeoip;

class Geoip
{
    public $driver;

    /**
     * Creates an instance of a geoapi driver.
     *
     * @param string $driver
     */
    public function __construct($driver)
    {
        switch ($driver) {
            case 'userinfo.io':
                $this->driver = new Userinfo();
                break;
            case 'freegeoip.net':
                $this->driver = new Freegeoip();
                break;
            default:
                $this->driver = null;
        }
    }
}
