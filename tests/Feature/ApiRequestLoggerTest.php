<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ApiRequestLoggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_logs_request_with_correct_metadata(): void
    {
        Log::spy();

        $response = $this->withHeaders([
            'User-Agent' => 'PostmanRuntime/7.36.0',
        ])->get('/up');

        $response->assertStatus(200);

        Log::shouldHaveReceived('info')->once()->withArgs(function (string $message, array $context) {
            return str_contains($message, 'GET')
                && str_contains($message, '/up')
                && str_contains($message, '200 OK')
                && str_contains($message, '[Postman]')
                && $context['method'] === 'GET'
                && $context['uri'] === '/up'
                && $context['status_code'] === 200
                && $context['client'] === 'Postman';
        });
    }

    public function test_it_logs_safari_on_iphone_client(): void
    {
        Log::spy();

        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

        $this->withHeaders([
            'User-Agent' => $ua,
        ])->get('/up');

        Log::shouldHaveReceived('info')->once()->withArgs(function (string $message, array $context) {
            return str_contains($message, 'Safari • iPhone')
                && $context['client'] === 'Safari • iPhone'
                && $context['browser'] === 'Safari'
                && $context['device'] === 'iPhone';
        });
    }

    public function test_it_respects_ignore_paths_config(): void
    {
        Log::spy();

        Config::set('request-logger.ignore_paths', ['up']);

        $this->get('/up');

        Log::shouldNotHaveReceived('info');
    }

    public function test_it_respects_master_enabled_toggle(): void
    {
        Log::spy();

        Config::set('request-logger.enabled', false);

        $this->get('/up');

        Log::shouldNotHaveReceived('info');
    }

    public function test_it_includes_authenticated_user_in_log(): void
    {
        Log::spy();

        $user = User::factory()->create([
            'email' => 'testuser@example.com',
        ]);

        $this->actingAs($user)->get('/up');

        Log::shouldHaveReceived('info')->once()->withArgs(function (string $message, array $context) {
            return str_contains($message, 'testuser@example.com')
                && $context['user'] === 'testuser@example.com';
        });
    }
}
