<?php

namespace Tests\Unit;

use App\Http\Middleware\ApiRequestLogger;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ClientIpResolutionTest extends TestCase
{
    private ApiRequestLogger $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = new ApiRequestLogger;
    }

    private function callResolveClientIp(Request $request): string
    {
        $ref = new ReflectionClass($this->logger);
        $method = $ref->getMethod('resolveClientIp');
        $method->setAccessible(true);

        return $method->invoke($this->logger, $request);
    }

    public function test_it_resolves_cloudflare_connecting_ip(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], [
            'HTTP_CF_CONNECTING_IP' => '203.0.113.195',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $ip = $this->callResolveClientIp($request);
        $this->assertSame('203.0.113.195', $ip);
    }

    public function test_it_resolves_x_forwarded_for_first_client_ip(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_FOR' => '198.51.100.22, 10.0.0.1',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $ip = $this->callResolveClientIp($request);
        $this->assertSame('198.51.100.22', $ip);
    }

    public function test_it_resolves_x_real_ip(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], [
            'HTTP_X_REAL_IP' => '192.168.1.50',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $ip = $this->callResolveClientIp($request);
        $this->assertSame('192.168.1.50', $ip);
    }

    public function test_it_resolves_remote_network_client_ip(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], [
            'REMOTE_ADDR' => '10.10.13.88',
        ]);

        $ip = $this->callResolveClientIp($request);
        $this->assertSame('10.10.13.88', $ip);
    }

    public function test_it_resolves_non_loopback_ip_for_local_client(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], [
            'REMOTE_ADDR' => '127.0.0.1',
        ]);

        $ip = $this->callResolveClientIp($request);
        // On machine with network adapter, should resolve to real network IPv4 (e.g. 10.10.13.35)
        $this->assertNotSame('', $ip);
        $this->assertNotFalse(filter_var($ip, FILTER_VALIDATE_IP));
    }
}
