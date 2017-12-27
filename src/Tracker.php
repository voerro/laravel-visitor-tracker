<?php

namespace Voerro\Laravel\VisitorTracker;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;
use Voerro\Laravel\VisitorTracker\Model\Visit;

class Tracker
{
    public static function recordVisit($agent = null)
    {
        if (!self::shouldTrackAuthenticatedUser()) {
            return;
        }

        $data = self::getVisitData($agent ?: request()->userAgent());

        // Determine if the request is a login attempt
        if (request()->route()
        && '/' . request()->route()->uri == config('visitortracker.login_attempt.url')
        && $data['method'] == config('visitortracker.login_attempt.method')
        && $data['is_ajax'] == config('visitortracker.login_attempt.is_ajax')) {
            $data['is_login_attempt'] = true;
        }

        if (!self::shouldRecordRequest($data)) {
            return;
        }

        $visit = Visit::create($data);

        // Collect the geoip data if needed
        if (config('visitortracker.geoip_on')) {
            $geoip = new Geoip(config('visitortracker.geoip_driver'));

            if ($geoip->driver) {
                $visit->ip = '112.205.241.105'; // DEBUG
                if ($geoip = $geoip->driver->getDataFor($visit)) {
                    $visit->update([
                        'lat' => $geoip->latitude(),
                        'long' => $geoip->longitude(),
                        'country' => $geoip->country(),
                        'country_code' => $geoip->countryCode(),
                        'city' => $geoip->city(),
                    ]);
                }
            }
        }

        return $visit;
    }

    /**
     * Determine if the authenticated user should be tracked
     *
     * @return boolean
     */
    protected static function shouldTrackAuthenticatedUser()
    {
        if (auth()->check()) {
            foreach (config('visitortracker.dont_track_users') as $fields) {
                $conditionsMet = 0;
                foreach ($fields as $field => $value) {
                    if (auth()->user()->{$field} == $value) {
                        $conditionsMet++;
                    }
                }

                if ($conditionsMet == count($fields)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determine if the request/visit should be recorded
     *
     * @return boolean
     */
    protected static function shouldRecordRequest($data)
    {
        foreach (config('visitortracker.dont_record') as $fields) {
            $conditionsMet = 0;
            foreach ($fields as $field => $value) {
                if ($data[$field] == $value) {
                    $conditionsMet++;
                }
            }

            if ($conditionsMet == count($fields)) {
                return false;
            }
        }

        return true;
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

        // Browser language
        preg_match_all('/([a-z]{2})-[A-Z]{2}/', request()->server('HTTP_ACCEPT_LANGUAGE'), $matches);

        $lang = count($matches) && count($matches[0]) ? $matches[0][0] : '';
        $langFamily = count($matches) && count($matches[1]) ? $matches[1][0] : '';

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

            'browser_language_family' => $langFamily,
            'browser_language' => $lang,
        ];
    }
}
