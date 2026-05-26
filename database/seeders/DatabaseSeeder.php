<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'id' => 2,
            'name' => 'manager',
            'email' => 'pm@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'manager',
        ]);

        User::factory()->create([
            'id' => 3,
            'name' => 'employee',
            'email' => 'emp@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'employee',
        ]);

        $project1 = Project::factory()->create([
            'id' => 1,
            'name' => 'Kokan Soundarya',
            'description' => 'Kokan Soundarya is a homestay service provider in the Konkan region of Maharashtra, India. They needs a brand site to showcase their services, attract customers, and provide information about their offerings.',
            'status' => 'in_progress',
            'deadline' => now()->addDays(30),
            'created_by' => 2,
        ]);

        $project2 = Project::factory()->create([
            'id' => 2,
            'name' => 'Art life studio',
            'description' => 'Art Life Studio is an art gallery and studio that needs a website to showcase their artists, exhibitions, and art classes. The website should provide information about the studio, upcoming events, and allow visitors to contact the studio for inquiries.',
            'status' => 'in_progress',
            'deadline' => now()->addDays(30),
            'created_by' => 2,
        ]);

        $project1->users()->attach([2, 3]);
        $project2->users()->attach([2, 3]);

        Task::insert([
            [
                'id' => 1,
                'project_id' => 1,
                'title' => 'Design Homepage',
                'description' => 'Create a visually appealing homepage that highlights the key features of Kokan Soundarya and encourages visitors to explore the site.',
                'status' => 'in_progress',
                'assignee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 2,
                'project_id' => 1,
                'title' => 'Develop Booking System',
                'description' => 'Implement a booking system that allows customers to check availability, make reservations, and receive confirmation emails.',
                'status' => 'in_progress',
                'assignee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 3,
                'project_id' => 2,
                'title' => 'Create Artist Profiles',
                'description' => 'Develop individual artist profile pages that showcase their work, biography, and upcoming exhibitions.',
                'status' => 'in_progress',
                'assignee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id' => 4,
                'project_id' => 2,
                'title' => 'Set Up Contact Form',
                'description' => 'Implement a contact form that allows visitors to send inquiries about art classes and exhibitions.',
                'status' => 'in_progress',
                'assignee_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
