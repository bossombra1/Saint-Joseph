<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@storosario.ph'],
            [
                'name' => 'Saint-JosephAdmin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'publicojamesmangao25@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        // 2. Sample Mass Schedules
        $schedules = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => '',
                'mass_type' => 'sunday',
                'day_of_week' => ['Sunday'],
                'time' => ['06:30', '08:30', '10:00', '15:00', '16:30', '18:00'],
                'is_active' => true,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => '',
                'mass_type' => 'weekday',
                'day_of_week' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'time' => ['18:00'],
                'is_active' => true,
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'title' => '',
                'mass_type' => 'saturday',
                'day_of_week' => ['Saturday'],
                'time' => ['06:30', '18:00'],
                'is_active' => true,
            ],
        ];

        foreach ($schedules as $s) {
            \App\Models\MassSchedule::updateOrCreate(['title' => $s['title']], $s);
        }

        // 3. Sample Announcements
        \App\Models\Announcement::updateOrCreate(
            ['title' => 'Welcome to our new website!'],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'content' => 'We are happy to announce the launch of our new parish management system. You can now submit mass intentions online!',
                'is_published' => true,
                'created_by' => $admin->id,
            ]
        );

        // 4. Sample Events
        \App\Models\Event::updateOrCreate(
            ['title' => 'Parish Fiesta 2026'],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'description' => 'Join us for our annual parish fiesta celebrations.',
                'event_date' => now()->addMonths(2),
                'event_time' => [['time' => '09:00:00', 'title' => 'Main Mass']],
                'is_published' => true,
            ]
        );

        // 5. Sample Staff User
        User::updateOrCreate(
            ['email' => 'staff@storosario.ph'],
            [
                'name' => 'Parish Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // 6. Sample SocCom User
        User::updateOrCreate(
            ['email' => 'soccom@storosario.ph'],
            [
                'name' => 'SocCom Team',
                'password' => Hash::make('password'),
                'role' => 'soccom',
            ]
        );
    }   
}
