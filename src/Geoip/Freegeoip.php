<?php

namespace Voerro\Laravel\VisitorTracker\Geoip;

class Freegeoip extends Driver
{
    protected function getEndpoint($ip)
    {
        return "http://freegeoip.net/json/{$ip}";
    }

    public function latitude()
    {
        return $this->data->latitude;
    }

    public function longitude()
    {
        return $this->data->longitude;
    }

    public function country()
    {
        return $this->data->country_name;
    }

    public function countryCode()
    {
        return $this->data->country_code;
    }

    public function city()
    {
        return $this->data->city;
    }
}
