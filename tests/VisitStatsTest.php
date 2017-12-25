<?php

namespace Voerro\VisitStats\Test;

use Voerro\VisitStats\Facades\Tracker;

class VisitStatsTest extends TestCase
{
    public function testAddition()
    {
        $result = Tracker::add(17, 3);

        $this->assertEquals(20, $result);
    }
}
