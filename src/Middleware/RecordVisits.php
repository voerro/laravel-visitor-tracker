<?php

namespace Voerro\VisitStats\Middleware;

use Closure;

class RecordVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        echo 'Middleware: RecordVisits';

        return $next($request);
    }
}
