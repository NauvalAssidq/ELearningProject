<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $studentRole = Role::firstOrCreate(
            ['slug' => 'student'],
            ['name' => 'Student']
        );

        $lecturerRole = Role::firstOrCreate(
            ['slug' => 'lecturer'],
            ['name' => 'Lecturer']
        );

        // 2. Create Teacher User
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@techalearn.com'],
            [
                'name' => 'Teacher User',
                'password' => Hash::make('password'),
            ]
        );
        
        // Attach role if not already attached
        if (!$teacher->roles()->where('slug', 'lecturer')->exists()) {
            $teacher->roles()->attach($lecturerRole);
        }

        // 3. Create Student User
        $student = User::firstOrCreate(
            ['email' => 'student@techalearn.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
            ]
        );

        if (!$student->roles()->where('slug', 'student')->exists()) {
            $student->roles()->attach($studentRole);
        }
        
        $this->command->info('Roles and Users seeded successfully!');
        $this->command->info('Teacher: teacher@techalearn.com / password');
        $this->command->info('Student: student@techalearn.com / password');
    }
}
