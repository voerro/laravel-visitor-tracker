<?php

namespace Voerro\Laravel\VisitorTracker\Geoip;

use Voerro\Laravel\VisitorTracker\Models\Visit;
use GuzzleHttp\Client;

abstract class Driver
{
    protected $data;

    public function getDataFor(Visit $visit)
    {
        $client = new Client();

        $response = $client->get($this->getEndpoint($visit->ip));

        if ($response->getStatusCode() == 200) {
            $this->data = json_decode($response->getBody()->getContents());

            return $this;
        }

        return null;
    }

    abstract protected function getEndpoint($ip);

    abstract public function latitude();

    abstract public function longitude();

    abstract public function country();

    abstract public function countryCode();

    abstract public function city();
}
