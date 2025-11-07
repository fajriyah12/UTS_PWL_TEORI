<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@orrea.test'],
            [
                'id' => (string) Str::uuid(),
                'name' => "Admin ORR'EA",
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'organizer@orrea.test'],
            [
                'id' => (string) Str::uuid(),
                'name' => "ORR'EA Organizer",
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@orrea.test'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // panggil seeder lain seperti biasa
        $this->call([
            EventSeeder::class,
        ]);
    }
}
