<?php

namespace Voerro\Laravel\VisitorTracker\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Voerro\Laravel\VisitorTracker\Model\Visit;
use Voerro\Laravel\VisitorTracker\Geoip;

class GetGeoipData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $visit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Visit $visit)
    {
        $this->visit = $visit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (config('visitortracker.geoip_on')) {
            $geoip = new Geoip(config('visitortracker.geoip_driver'));

            if ($geoip->driver) {
                if ($geoip = $geoip->driver->getDataFor($this->visit)) {
                    $this->visit->update([
                        'lat' => $geoip->latitude(),
                        'long' => $geoip->longitude(),
                        'country' => $geoip->country(),
                        'country_code' => $geoip->countryCode(),
                        'city' => $geoip->city(),
                    ]);
                }
            }
        }
    }
}
