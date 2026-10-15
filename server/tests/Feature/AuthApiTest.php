<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_invalid_login_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'missing@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_inactive_admin_cannot_log_in(): void
    {
        $role = Role::create([
            'name' => 'Editor',
            'slug' => 'editor',
        ]);

        $user = User::create([
            'name' => 'Inactive Editor',
            'email' => 'inactive@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
            'role_id' => $role->id,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(403)
            ->assertJson(['success' => false]);
    }
}