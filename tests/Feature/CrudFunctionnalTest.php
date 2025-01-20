<?php

namespace Tests\Feature;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

use Tests\TestCase;

class CrudFunctionnalTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        // config  initiale de mon env avant le test
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_admin_can_get_all_profiles()
    {
        $response = $this->actingAs($this->user, 'api') // Authentification avec l'utilisateur admin
            ->getJson('/api/admin/profiles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
            ]);
    }

    public function test_admin_can_get_single_profile()
    {
        $profile = Profil::factory()->create();


        $response = $this->actingAs($this->user, 'api')
            ->getJson('/api/admin/profiles/' . $profile->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200
            ]);

        // teste un profil qui n'existe pas
        $nonExistentId = 0011100000100;
        $response = $this->getJson('/api/admin/profiles/' . $nonExistentId);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Profile not found.',
            ]);
    }


    public function test_create_profile_without_image()
    {
        // je prépare les données de la requête sans image
        $data = [
            'lastname' => 'john',
            'firstname' => 'doe',
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/admin/profiles', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'status' => 201
            ]);
    }

    public function test_create_profile_with_image()
    {
        // je Prépare les données de la requête image
        $data = [
            'lastname' => 'Doe',
            'firstname' => 'John',
            'image' => UploadedFile::fake()->image('profile.jpg', 600, 400),
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/admin/profiles', $data);
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'status' => 201
            ]);
    }


    public function test_admin_can_delete_profile()
    {
        $profile = Profil::factory()->create();

        $response = $this->actingAs($this->user, 'api')
            ->deleteJson('/api/admin/profiles/' . $profile->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
                'message' => 'profil successfully deleted',
            ]);

        // je check que le profil a bien été supprimé de la base de données
        $this->assertDatabaseMissing('profils', [
            'id' => $profile->id,
        ]);

        // je teste un profil qui n'existe pas
        $nonExistentId = 0011100000545500;
        $response = $this->deleteJson('/api/admin/profiles/' . $nonExistentId);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'Profile not found',
            ]);
    }

    public function test_admin_can_update_profile()
    {
        $profile = Profil::factory()->create();

        // je Cree une image factice pour l'upload
        $image = UploadedFile::fake()->image('profile.jpg');

        // j'envoie la requête POST au lieu de PUT pour mettre à jour le profil avec une image
        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/admin/profiles/' . $profile->id, [
                'lastname' => 'new lastname',
                'firstname' => 'new Firstname',
                'statuses_id' => rand(1, 3),
                'image' => $image,
            ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
            ]);
    }


    public function test_get_all_profiles_with_status_1()
    {
        $response = $this->getJson('/api/profiles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 200,
            ]);
    }
}
