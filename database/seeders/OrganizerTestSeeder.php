<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\Venue;
use App\Models\TicketType;
use Illuminate\Support\Str;

class OrganizerTestSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user organizer
        $organizerUser = User::create([
            'name' => 'Demo Organizer',
            'email' => 'organizer@orre.com',
            'password' => bcrypt('password123'),
            'role' => 'organizer'
        ]);

        // Buat organizer profile
        $organizer = Organizer::create([
            'user_id' => $organizerUser->id,
            'name' => 'ORR\'EA Entertainment',
            'slug' => 'orrea-entertainment',
            'contact_email' => 'contact@orrea.com',
            'contact_phone' => '08123456789',
            'bio' => 'Penyelenggara konser terpercaya di Indonesia',
            'logo_path' => 'organizers/orrea-logo.png'
        ]);

        // Buat venue
        $venue = Venue::create([
            'name' => 'Gelora Bung Karno',
            'address' => 'Jl. Pintu Satu Senayan, Jakarta',
            'city' => 'Jakarta',
            'capacity' => 80000
        ]);

        // Buat event
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'venue_id' => $venue->id,
            'title' => 'Coldplay Music of the Spheres World Tour',
            'slug' => 'coldplay-music-of-the-spheres-2024',
            'description' => 'Nikmati konser spektakuler Coldplay hanya di ORR\'EA Ticketing.',
            'image' => 'events/coldplay-banner.jpg',
            'location' => 'Jakarta',
            'start_time' => now()->addDays(30),
            'end_time' => now()->addDays(30)->addHours(4),
            'status' => 'published'
        ]);

        // Buat tiket types
        $ticketTypes = [
            [
                'name' => 'Regular',
                'price' => 296875,
                'quota' => 500,
                'per_user_limit' => 2
            ],
            [
                'name' => 'VIP',
                'price' => 845285,
                'quota' => 100,
                'per_user_limit' => 1
            ],
            [
                'name' => 'Festival',
                'price' => 484834,
                'quota' => 300,
                'per_user_limit' => 3
            ]
        ];

        foreach ($ticketTypes as $type) {
            TicketType::create(array_merge($type, [
                'event_id' => $event->id,
                'sales_start' => now(),
                'sales_end' => now()->addDays(25)
            ]));
        }

        $this->command->info('✅ Organizer test data created successfully!');
        $this->command->info('👤 Login: organizer@orre.com / password123');
    }
}