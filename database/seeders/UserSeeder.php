<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'coach@example.com'],
            [
                'name' => 'Head Coach',
                'role' => Role::Coach,
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'player@example.com'],
            [
                'name' => 'First Player',
                'role' => Role::Player,
                'password' => Hash::make('password'),
            ]
        );
    }
}
