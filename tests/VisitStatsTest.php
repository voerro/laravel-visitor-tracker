<?php

namespace Voerro\VisitStats\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Voerro\VisitStats\Facades\Tracker;
use Voerro\VisitStats\Model\Visit;

class VisitStatsTest extends TestCase
{
    use RefreshDatabase;

    public function testRecordBasicInformation()
    {
        $result = Tracker::recordVisit();

        $this->assertCount(1, Visit::all());

        $visit = Visit::first();

        $this->assertNotNull($visit->ip);
        $this->assertNotNull($visit->url);
        $this->assertNotNull($visit->user_agent);
        $this->assertEquals('GET', $visit->method);
        $this->assertFalse($visit->is_ajax);
    }
}
