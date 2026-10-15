<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_init_endpoint_returns_success(): void
    {
        $response = $this->getJson('/api/v1/init');

        $response->assertOk()->assertJson(['success' => true]);
    }

    public function test_public_data_endpoints_return_success(): void
    {
        $endpoints = [
            '/api/v1/news',
            '/api/v1/events',
            '/api/v1/services',
            '/api/v1/government/members',
            '/api/v1/government/departments',
            '/api/v1/explore',
            '/api/v1/programs',
            '/api/v1/resources',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);

            $response->assertOk()->assertJson(['success' => true]);
        }
    }
}