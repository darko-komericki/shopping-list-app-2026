<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\SeedUserData;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        (new SeedUserData)($user);
    }
}
