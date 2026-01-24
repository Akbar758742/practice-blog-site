<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\UserStatus;
use App\UserType;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('12345'),
                'type' => UserType::SuperAdmin,
                'status' => UserStatus::ACTIVE,
            ]
        );

        // Create Editor
        User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'username' => 'editor',
                'password' => Hash::make('12345'),
                'type' => UserType::Admin, // Access backend
                'status' => UserStatus::ACTIVE,
            ]
        );

        // Create Author
        User::firstOrCreate(
            ['email' => 'author@example.com'],
            [
                'name' => 'Author User',
                'username' => 'author',
                'password' => Hash::make('12345'),
                'type' => UserType::Admin, // Access backend
                'status' => UserStatus::ACTIVE,
            ]
        );
    }
}
