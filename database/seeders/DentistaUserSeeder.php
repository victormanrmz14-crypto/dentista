<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DentistaUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rocio.perez@consultorio.com'],
            [
                'name' => 'Dra. Rocio Perez',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
    }
}
