<?php

namespace Voerro\Laravel\VisitorTracker;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\OperatingSystem;
use Voerro\Laravel\VisitorTracker\Model\Visit;
use GuzzleHttp\Client;

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
        if (request()->route()
        && '/' . request()->route()->uri == config('visitortracker.login_attempt.url')
        && $data['method'] == config('visitortracker.login_attempt.method')
        && $data['is_ajax'] == config('visitortracker.login_attempt.is_ajax')) {
            $data['is_login_attempt'] = true;
        }

        $visit = Visit::create($data);

        // Collect the geoip data if needed
        if (config('visitortracker.geoip_on')) {
            $client = new Client();

            $response = $client->get('https://api.userinfo.io/userinfos?ip_address=' . $data['ip']);

            $json = json_decode($response->getBody()->getContents());

            $visit->update([
                'lat' => $json->position->latitude ?: null,
                'long' => $json->position->longitude ?: null,
                'country' => $json->country->name ?: null,
                'country_code' => $json->country->code ?: null,
                'city' => $json->city->name ?: null,
            ]);
        }

        return $visit;
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
