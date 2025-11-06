<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Organizer;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email'=>'admin@orrea.local'],
            ['name'=>'Admin ORR\'EA','password'=>Hash::make('password'),'email_verified_at'=>now(),'role'=>'admin']
        );

        $orgUser = User::firstOrCreate(
            ['email'=>'org@orrea.local'],
            ['name'=>'Organizer One','password'=>Hash::make('password'),'email_verified_at'=>now(),'role'=>'organizer']
        );

        Organizer::firstOrCreate(
            ['user_id'=>$orgUser->id],
            ['name'=>'Organizer One','slug'=>'organizer-one','contact_email'=>'org@orrea.local']
        );

        User::firstOrCreate(
            ['email'=>'user@orrea.local'],
            ['name'=>'User Biasa','password'=>Hash::make('password'),'email_verified_at'=>now(),'role'=>'user']
        );
    }
}
