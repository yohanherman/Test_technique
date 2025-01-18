<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthFunctionnalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_receives_token_on_longin_and_can_access_protected_route()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/admin/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // je check que la réponse contient la clé 'authorization' et 'token'
        $response->assertStatus(200)
            ->assertJsonStructure([
                'authorization' => [
                    'token',
                    'type',
                ],
                'user',
                'status',
                'success',
            ]);

        $token = $response->json('authorization.token');

        // je use le token pour accéder à une route protégée
        $protectedResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/profiles');

        $protectedResponse->assertStatus(200)
            ->assertJson([
                'success' => 'true',
            ]);
    }

    public function test_admin_can_register()
    {
        $adminData = [
            'name' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'roles' => 'admin',
        ];

        $response = $this->postJson('/api/admin/auth/register', $adminData);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'roles' => 'admin',
        ]);

        $response->assertJson([
            'user' => [
                'name' => 'johndoe',
                'email' => 'johndoe@example.com',
            ],
            'status' => 201,
            'success' => true,
        ]);
    }

    public function test_admin_can_logout()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->withHeader('Authorization', 'Bearer' . $token)
            ->postJson('/api/admin/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => '200',
                'success' => true,
                'message' => 'Successfully logged out'
            ]);

        // je check que l'utilisateur est déconnecté
        $this->assertNull(Auth::user());
    }

    public function test_admin_can_refresh_token()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->withHeader('Authorization', 'Bearer' . $token)
            ->postJson('/api/admin/auth/refresh');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'authorization' => [
                'token',
                'type',
                'expires_in'
            ],
            'user',
            'status',
            'message'
        
        ]);
    }
}
