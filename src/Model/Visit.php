<?php

namespace Voerro\VisitStats\Model;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $table = 'visitstats_visits';

    protected $guarded = [];
}
