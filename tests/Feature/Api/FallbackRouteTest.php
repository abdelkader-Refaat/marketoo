<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FallbackRouteTest extends TestCase
{
    /**
     * Test that the API fallback route returns the correct response.
     *
     * @return void
     */
    public function test_api_fallback_route_returns_not_found_response()
    {
        // Disable middleware to avoid database connection issues
        $response = $this->withoutMiddleware()
                         ->get('/api/v1/non-existent-route');

        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Route not found',
                 ]);
    }
}
