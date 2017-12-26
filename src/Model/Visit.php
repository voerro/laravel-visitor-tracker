<?php

namespace Voerro\VisitStats\Model;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'visitstats_visits';

    protected $guarded = [];

    protected $casts = [
        'is_ajax' => 'boolean',
        'is_login_attempt' => 'boolean',
    ];
}
