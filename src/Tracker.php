<?php

namespace Voerro\VisitStats;

use Voerro\VisitStats\Model\Visit;

class Tracker
{
    public static function recordVisit()
    {
        Visit::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'ip' => request()->ip(),
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'is_ajax' => request()->ajax(),
            'user_agent' => request()->userAgent(),
            'browser_language' => request()->server('HTTP_ACCEPT_LANGUAGE'),
        ]);
    }
}
