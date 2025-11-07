<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Organizer;
use App\Models\Venue;
use App\Models\Event;
use App\Models\TicketType;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🧍 1. Buat 3 user utama
        $admin = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin ORR\'EA',
            'email' => 'admin@orrea.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $organizerUser = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'ORR\'EA Organizer',
            'email' => 'organizer@orrea.test',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'email_verified_at' => now(),
        ]);

        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Regular User',
            'email' => 'user@orrea.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // 🧾 2. Organizer profil
        $organizer = Organizer::create([
            'id' => (string) Str::uuid(),
            'user_id' => $organizerUser->id,
            'name' => 'ORR\'EA Entertainment',
            'slug' => 'orrea-entertainment',
            'contact_email' => $organizerUser->email,
            'contact_phone' => '08123456789',
            'bio' => 'Event organizer resmi ORR\'EA yang menghadirkan konser internasional dan festival musik besar di Indonesia.',
        ]);

        // 🏟️ 3. Venue utama
        $venues = collect([
            ['Jakarta International Expo', 'Kemayoran, Jakarta'],
            ['Gelora Bung Karno', 'Senayan, Jakarta'],
            ['Istora Senayan', 'Gelora, Jakarta'],
            ['Lapangan D Senayan', 'Jakarta Pusat'],
            ['Pantai Karnaval Ancol', 'Jakarta Utara'],
        ])->map(fn($v) => Venue::create([
            'id' => (string) Str::uuid(),
            'organizer_id' => $organizer->id,
            'name' => $v[0],
            'slug' => Str::slug($v[0]),
            'city' => 'Jakarta',
            'address' => $v[1],
            'capacity' => rand(5000, 30000),
        ]));

        // 🎤 4. Daftar tema & artis
        $themes = [
            ['title' => 'The Weeknd World Tour', 'artist' => 'The Weeknd'],
            ['title' => 'Coldplay Music of the Spheres', 'artist' => 'Coldplay'],
            ['title' => 'Payung Teduh Live', 'artist' => 'Payung Teduh'],
            ['title' => 'Djakarta Warehouse Project', 'artist' => 'Various DJs'],
            ['title' => 'Java Jazz Festival', 'artist' => 'Jazz Musicians'],
            ['title' => 'Rock Fest Indonesia', 'artist' => 'Rock Bands'],
            ['title' => 'K-Pop Festival', 'artist' => 'K-Pop Stars'],
            ['title' => 'EDM Rave Party', 'artist' => 'Top EDM DJs'],
            ['title' => 'Indie Music Fest', 'artist' => 'Indie Bands'],
            ['title' => 'Sound of Asia', 'artist' => 'Asian Pop Artists'],
        ];

        // 🎫 5. Generate 20 event acak
        foreach (range(1, 20) as $i) {
            $theme = $themes[array_rand($themes)];
            $venue = $venues->random();
            $start = now()->addDays(rand(10, 200));
            $end = (clone $start)->addHours(rand(3, 8));

            $event = Event::create([
                'id' => (string) Str::uuid(),
                'organizer_id' => $organizer->id,
                'venue_id' => $venue->id,
                'title' => "{$theme['title']} #" . $i,
                'slug' => Str::slug($theme['title'] . '-' . $i),
                'description' => "Nikmati konser spektakuler {$theme['artist']} hanya di ORR'EA Ticketing. Dapatkan pengalaman musik terbaik!",
                'banner_path' => null,
                'start_at' => $start,
                'end_at' => $end,
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // tiket dummy (3 kategori)
            TicketType::insert([
                [
                    'id' => (string) Str::uuid(),
                    'event_id' => $event->id,
                    'name' => 'Regular',
                    'price' => rand(200000, 350000),
                    'quota' => rand(100, 300),
                    'sold' => rand(0, 50),
                    'per_user_limit' => 4,
                    'sales_start' => now(),
                    'sales_end' => $end->subDays(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => (string) Str::uuid(),
                    'event_id' => $event->id,
                    'name' => 'VIP',
                    'price' => rand(600000, 900000),
                    'quota' => rand(50, 100),
                    'sold' => rand(0, 30),
                    'per_user_limit' => 2,
                    'sales_start' => now(),
                    'sales_end' => $end->subDays(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => (string) Str::uuid(),
                    'event_id' => $event->id,
                    'name' => 'Festival',
                    'price' => rand(350000, 500000),
                    'quota' => rand(200, 500),
                    'sold' => rand(0, 70),
                    'per_user_limit' => 4,
                    'sales_start' => now(),
                    'sales_end' => $end->subDays(1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        $this->command->info('✅ Database ORR\'EA berhasil diisi:');
        $this->command->info('- 3 Users (admin, organizer, user)');
        $this->command->info('- 1 Organizer profile');
        $this->command->info('- 5 Venues');
        $this->command->info('- 20 Events + 60 TicketTypes');
    }
}
