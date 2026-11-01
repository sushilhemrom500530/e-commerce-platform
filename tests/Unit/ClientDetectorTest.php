<?php

namespace Tests\Unit;

use App\Support\ClientDetector;
use PHPUnit\Framework\TestCase;

class ClientDetectorTest extends TestCase
{
    public function test_it_detects_safari_on_iphone(): void
    {
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Safari', $result['browser']);
        $this->assertSame('iOS', $result['platform']);
        $this->assertSame('iPhone', $result['device']);
        $this->assertSame('Safari • iPhone', $result['display']);
    }

    public function test_it_detects_chrome_on_windows(): void
    {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Chrome', $result['browser']);
        $this->assertSame('Windows', $result['platform']);
        $this->assertSame('Desktop', $result['device']);
        $this->assertSame('Chrome • Windows', $result['display']);
    }

    public function test_it_detects_chrome_on_iphone(): void
    {
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/120.0.6099.119 Mobile/15E148 Safari/604.1';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Chrome', $result['browser']);
        $this->assertSame('iPhone', $result['device']);
        $this->assertSame('Chrome • iPhone', $result['display']);
    }

    public function test_it_detects_chrome_on_android(): void
    {
        $ua = 'Mozilla/5.0 (Linux; Android 14; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.6099.144 Mobile Safari/537.36';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Chrome', $result['browser']);
        $this->assertSame('Android', $result['platform']);
        $this->assertSame('Chrome • Android', $result['display']);
    }

    public function test_it_detects_safari_on_macos(): void
    {
        $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Safari', $result['browser']);
        $this->assertSame('macOS', $result['platform']);
        $this->assertSame('Safari • macOS', $result['display']);
    }

    public function test_it_detects_postman(): void
    {
        $ua = 'PostmanRuntime/7.36.0';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Postman', $result['browser']);
        $this->assertSame('Postman', $result['display']);
    }

    public function test_it_detects_curl(): void
    {
        $ua = 'curl/8.4.0';
        $result = ClientDetector::detect($ua);

        $this->assertSame('cURL', $result['browser']);
        $this->assertSame('cURL', $result['display']);
    }

    public function test_it_detects_firefox_on_linux(): void
    {
        $ua = 'Mozilla/5.0 (X11; Linux x86_64; rv:120.0) Gecko/20100101 Firefox/120.0';
        $result = ClientDetector::detect($ua);

        $this->assertSame('Firefox', $result['browser']);
        $this->assertSame('Linux', $result['platform']);
        $this->assertSame('Firefox • Linux', $result['display']);
    }

    public function test_it_handles_null_or_empty_user_agent(): void
    {
        $result = ClientDetector::detect(null);

        $this->assertSame('Unknown', $result['browser']);
        $this->assertSame('Unknown', $result['platform']);
        $this->assertSame('Unknown Client', $result['display']);

        $emptyResult = ClientDetector::detect('');
        $this->assertSame('Unknown Client', $emptyResult['display']);
    }
}
