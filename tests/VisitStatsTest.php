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
        $visit = Tracker::recordVisit('Mozilla/5.0 (Linux; Android 6.0; Boost3 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/63.0.3239.111 Mobile Safari/537.36 Instagram 27.0.0.11.97 Android (23/6.0; 480dpi; 1080x1920; Highscreen; Boost3; BF169; mt6735; ru_RU)');

        $this->assertTrue($visit->is_mobile);

        $visit = Tracker::recordVisit('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

        $this->assertFalse($visit->is_mobile);
    }

    public function testDetermineIfTheVisitorIsBot()
    {
        $visit = Tracker::recordVisit('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

        $this->assertTrue($visit->is_bot);
        $this->assertEquals('Googlebot', $visit->bot);

        $visit = Tracker::recordVisit('Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; Google Web Preview Analytics) Chrome/41.0.2272.118 Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');

        $this->assertTrue($visit->is_bot);
        $this->assertEquals('Googlebot', $visit->bot);

        $visit = Tracker::recordVisit('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

        $this->assertEquals(false, $visit->is_bot);
    }

    public function testDetectBrowser()
    {
        $visit = Tracker::recordVisit('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

        $this->assertEquals('Firefox', $visit->browser_family);
        $this->assertEquals('Firefox 58.0', $visit->browser);

        $visit = Tracker::recordVisit('Mozilla/5.0 (Linux; Android 6.0; Boost3 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/63.0.3239.111 Mobile Safari/537.36 Instagram 27.0.0.11.97 Android (23/6.0; 480dpi; 1080x1920; Highscreen; Boost3; BF169; mt6735; ru_RU)');

        $this->assertEquals('Chrome Mobile', $visit->browser_family);
        $this->assertEquals('Chrome Mobile 63.0', $visit->browser);

        $visit = Tracker::recordVisit('Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36');

        $this->assertEquals('Chrome', $visit->browser_family);
        $this->assertEquals('Chrome 63.0', $visit->browser);
    }

    public function testDetectOS()
    {
        $visit = Tracker::recordVisit('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0');

        $this->assertEquals('GNU/Linux', $visit->os_family);
        $this->assertEquals('Ubuntu', $visit->os);

        $visit = Tracker::recordVisit('Mozilla/5.0 (Linux; Android 6.0; Boost3 Build/MRA58K; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/63.0.3239.111 Mobile Safari/537.36 Instagram 27.0.0.11.97 Android (23/6.0; 480dpi; 1080x1920; Highscreen; Boost3; BF169; mt6735; ru_RU)');

        $this->assertEquals('Android', $visit->os_family);
        $this->assertEquals('Android 6.0', $visit->os);

        $visit = Tracker::recordVisit('Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36');

        $this->assertEquals('Windows', $visit->os_family);
        $this->assertEquals('Windows 8.1', $visit->os);
    }
}
