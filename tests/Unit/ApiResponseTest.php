<?php

namespace Tests\Unit;

use App\Traits\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    use ApiResponse;

    public function test_standard_response_has_correct_structure_without_meta(): void
    {
        $response = $this->sendResponse(
            ['user' => ['id' => 1, 'name' => 'Jane']],
            'Success message',
            201
        );

        $data = $response->getData(true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(201, $data['statusCode']);
        $this->assertTrue($data['success']);
        $this->assertEquals('Success message', $data['message']);
        $this->assertEquals(['user' => ['id' => 1, 'name' => 'Jane']], $data['results']);
        $this->assertArrayNotHasKey('meta', $data);
        $this->assertArrayNotHasKey('data', $data);
    }

    public function test_paginated_response_contains_meta_and_results(): void
    {
        $items = collect([
            ['id' => 1, 'name' => 'iPhone 16', 'price' => 100000],
            ['id' => 2, 'name' => 'MacBook Pro', 'price' => 200000],
        ]);

        $paginator = new LengthAwarePaginator(
            $items,
            100, // total
            10,  // perPage
            1    // currentPage
        );

        $response = $this->sendResponse($paginator, 'Products retrieved successfully.', 200);

        $data = $response->getData(true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(200, $data['statusCode']);
        $this->assertTrue($data['success']);
        $this->assertEquals('Products retrieved successfully.', $data['message']);
        $this->assertArrayHasKey('meta', $data);
        $this->assertEquals([
            'currentPage' => 1,
            'limit' => 10,
            'totalResult' => 100,
            'totalPages' => 10,
        ], $data['meta']);
        $this->assertEquals($items->toArray(), $data['results']);
        $this->assertArrayNotHasKey('data', $data);
    }

    public function test_error_response_structure(): void
    {
        $response = $this->sendError('Validation Error', 400, [
            ['path' => 'email', 'message' => 'Invalid email'],
        ]);

        $data = $response->getData(true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(400, $data['statusCode']);
        $this->assertFalse($data['success']);
        $this->assertEquals('Validation Error', $data['message']);
        $this->assertEquals([
            ['path' => 'email', 'message' => 'Invalid email'],
        ], $data['errorMessages']);
        $this->assertArrayNotHasKey('meta', $data);
        $this->assertArrayNotHasKey('data', $data);
    }
}
