<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Organizer;
use App\Models\Venue;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\Hash;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Pastikan ada user organizer
        $orgUser = User::firstOrCreate(
            ['email' => 'organizer@orrea.test'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'ORR\'EA Organizer',
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'email_verified_at' => now(),
            ]
        );

        // 2) Pastikan ada organizer profile
        $organizer = Organizer::firstOrCreate(
            ['user_id' => $orgUser->id],
            [
                'id' => (string) Str::uuid(),
                'name' => 'ORR\'EA Entertainment',
                'slug' => 'orrea-entertainment',
                'contact_email' => $orgUser->email,
                'contact_phone' => '08123456789',
                'bio' => 'Organizer resmi ORR\'EA.',
            ]
        );

        // 3) Pastikan ada beberapa venues (UUID)
        if (Venue::count() < 3) {
            $base = [
                ['Jakarta International Expo', 'Kemayoran, Jakarta'],
                ['Gelora Bung Karno', 'Senayan, Jakarta'],
                ['Istora Senayan', 'Gelora, Jakarta'],
                ['Lapangan D Senayan', 'Jakarta Pusat'],
                ['Pantai Karnaval Ancol', 'Jakarta Utara'],
            ];
            foreach ($base as $v) {
                Venue::firstOrCreate(
                    ['slug' => Str::slug($v[0])],
                    [
                        'id' => (string) Str::uuid(),
                        'organizer_id' => $organizer->id,
                        'name' => $v[0],
                        'city' => 'Jakarta',
                        'address' => $v[1],
                        'capacity' => rand(5000, 30000),
                    ]
                );
            }
        }

        $venues = Venue::pluck('id'); // ← kumpulkan UUID venue (bukan 0)

        // 4) Tema event
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

        // 5) Buat 20 event + ticket types
        foreach (range(1, 120) as $i) {
            $theme = $themes[array_rand($themes)];
            $venueId = $venues->random(); // ← selalu UUID valid, tidak mungkin 0
            $start = now()->addDays(rand(10, 200));
            $end = (clone $start)->addHours(rand(3, 8));

            $event = Event::create([
                'id' => (string) Str::uuid(),
                'organizer_id' => $organizer->id,
                'venue_id' => $venueId, // ← UUID benar
                'title' => "{$theme['title']} #$i",
                'slug' => Str::slug($theme['title'].'-'.$i),
                'description' => "Nikmati konser spektakuler {$theme['artist']} hanya di ORR'EA Ticketing.",
                'banner_path' => null,
                'start_at' => $start,
                'end_at' => $end,
                'status' => 'published',
            ]);

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
                    'sales_end' => (clone $end)->subDay(),
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
                    'sales_end' => (clone $end)->subDay(),
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
                    'sales_end' => (clone $end)->subDay(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
