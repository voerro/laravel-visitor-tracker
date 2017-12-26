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

    public function testDetermineIfTheVisitIsFromMobileDevice()
    {
        $result = Tracker::isMobile('Mozilla/5.0 (Linux; Android 6.0; Boost3 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/63.0.3239.111 Mobile Safari/537.36 Instagram 27.0.0.11.97 Android (23/6.0; 480dpi; 1080x1920; Highscreen; Boost3; BF169; mt6735; ru_RU)');

        $this->assertTrue($result);

        $result = Tracker::isMobile('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

        $this->assertFalse($result);
    }

    public function testDetermineIfTheVisitorIsBot()
    {
        $bot = Tracker::getBot('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

        $this->assertEquals('Googlebot', $bot);

        $bot = Tracker::getBot('Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; Google Web Preview Analytics) Chrome/41.0.2272.118 Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

        $this->assertEquals('Googlebot', $bot);

        $bot = Tracker::getBot('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

        $this->assertEquals(false, $bot);
    }
}
