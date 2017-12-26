<?php

namespace Voerro\VisitStats;

use Voerro\VisitStats\Model\Visit;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;

class Tracker
{
    public static function recordVisit($agent = null)
    {
        $agent = $agent ?: request()->userAgent();

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

        return Visit::create([
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
        ]);
    }
}
