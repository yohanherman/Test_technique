<?php

namespace Database\Factories;

use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profil>
 */
class ProfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lastname' => $this->faker->lastName,
            'firstname' => $this->faker->firstName,
            'image' => $this->faker->imageUrl(300, 300, 'people'),
            'statuses_id' => $this->faker->numberBetween(1, 3),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
