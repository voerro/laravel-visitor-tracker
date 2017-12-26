<?php

namespace Voerro\VisitStats;

use Voerro\VisitStats\Model\Visit;

class Tracker
{
    public static function recordVisit()
    {
        $agent = request()->userAgent();
        $bot = self::getBot($agent);

        Visit::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip' => request()->ip(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'is_ajax' => request()->ajax(),

            'user_agent' => $agent,
            'is_mobile' => self::isMobile($agent),
            'is_bot' => !!$bot,
            'bot' => $bot ?: null,

            'browser_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
        ]);
    }

    public static function isMobile($agent)
    {
        return stripos($agent, 'Mobile') !== false;
    }

    public static function getBot($agent)
    {
        if (stripos($agent, 'Googlebot')) {
            return 'Googlebot';
        } elseif (false) {
            return '';
        }

        return false;
    }
}
