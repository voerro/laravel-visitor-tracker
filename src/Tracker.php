<?php

namespace Voerro\Laravel\VisitorTracker;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;
use Voerro\Laravel\VisitorTracker\Model\Visit;

class Tracker
{
    public static function recordVisit($agent = null)
    {
        // Check if the user should be tracked
        if (auth()->check()) {
            foreach (config('visitortracker.dont_track_users') as $fields) {
                $conditionsMet = 0;
                foreach ($fields as $field => $value) {
                    if (auth()->user()->{$field} == $value) {
                        $conditionsMet++;
                    }
                }

                if ($conditionsMet == count($fields)) {
                    return;
                }
            }
        }

        $data = self::getVisitData($agent ?: request()->userAgent());

        // Check if the request should be recorded
        foreach (config('visitortracker.dont_record') as $fields) {
            $conditionsMet = 0;
            foreach ($fields as $field => $value) {
                if ($data[$field] == $value) {
                    $conditionsMet++;
                }
            }

            if ($conditionsMet == count($fields)) {
                return;
            }
        }

        // Determine if the request is a login attempt
        if ('/' . request()->route()->uri == config('visitortracker.login_attempt.url')
        && $data['method'] == config('visitortracker.login_attempt.method')
        && $data['is_ajax'] == config('visitortracker.login_attempt.is_ajax')) {
            $data['is_login_attempt'] = true;
        }

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
