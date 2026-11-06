<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test storefront catalog returns successful response.
     */
    public function test_the_storefront_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test health check route returns standardized json response.
     */
    public function test_health_check_returns_standardized_json(): void
    {
        $response = $this->getJson('/health');

        $response->assertStatus(200)
            ->assertJson([
                'statusCode' => 200,
                'success' => true,
                'message' => 'PL Server is running smoothly.',
                'results' => [
                    'name' => 'PL Server',
                    'status' => 'online',
                ],
            ])
            ->assertJsonStructure([
                'statusCode',
                'success',
                'message',
                'results' => [
                    'name',
                    'status',
                    'environment',
                    'version',
                    'php_version',
                    'laravel_version',
                    'timestamp',
                    'services' => [
                        'database',
                    ],
                    'endpoints' => [
                        'health',
                        'auth',
                    ],
                ],
            ]);
    }
}
