<?php

namespace Database\Seeders;

use App\Models\statuses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (statuses::count() === 0) {
            statuses::create([
                'name' => ' active',
                'value' => 1
            ]);

            statuses::create([
                'name' => 'inactive',
                'value' => 2,
            ]);

            statuses::create([
                'name' => 'pending',
                'value' => 3
            ]);
        }
    }
}
