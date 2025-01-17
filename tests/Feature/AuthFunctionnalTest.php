<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthFunctionnalTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_token_and_can_access_protected_route()
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

        // je récupere le token de la réponse
        $token = $response->json('authorization.token');

        // je use le token pour accéder à une route protégée
        $protectedResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/profiles');

        // je check que l'utilisateur a accès
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
}
