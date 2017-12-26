<?php

namespace Voerro\Laravel\VisitorTracker;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;
use Voerro\Laravel\VisitorTracker\Model\Visit;

class Tracker
{
    public static function recordVisit($agent = null)
    {
        $agent = $agent ?: request()->userAgent();

        $data = self::getVisitData($agent);

        // dd(config('visitrotracker.dont_track')[0]['ip']);

        return Visit::create($data);
    }

    protected static function getVisitData($agent)
    {
        $dd = new DeviceDetector($agent);
        $dd->parse();

        $bot = null;
        if ($dd->isBot()) {
            $bot = $dd->getBot();
        }

        $os = $dd->getOs('version')
            ? $dd->getOs('name') . ' ' . $dd->getOs('version')
            : $dd->getOs('name');

        $browser = $dd->getClient('version')
            ? $dd->getClient('name') . ' ' . $dd->getClient('version')
            : $dd->getClient('name');

        return [
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip' => request()->ip(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'is_ajax' => request()->ajax(),

            'user_agent' => $agent,
            'is_mobile' => $dd->isMobile(),
            'is_bot' => $dd->isBot(),
            'bot' => $bot ? $bot['name'] : null,
            'os' => $os,
            'os_family' => OperatingSystem::getOsFamily($dd->getOs('short_name')),
            'browser_family' => $dd->getClient('name'),
            'browser' => $browser,

            'browser_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
        ];
    }
}
