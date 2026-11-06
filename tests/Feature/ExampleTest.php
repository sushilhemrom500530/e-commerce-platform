<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test root route returns professional json response.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

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
