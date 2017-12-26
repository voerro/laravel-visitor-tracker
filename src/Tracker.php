<?php

namespace Voerro\VisitStats;

use Voerro\VisitStats\Model\Visit;

class Tracker
{
    public static function recordVisit()
    {
        $agent = request()->userAgent();

        Visit::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip' => request()->ip(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'is_ajax' => request()->ajax(),
            'user_agent' => $agent,
            'is_mobile' => self::isMobile($agent),
            'browser_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
        ]);
    }

    public static function isMobile($agent)
    {
        return stripos($agent, 'Mobile') !== false;
    }
}
