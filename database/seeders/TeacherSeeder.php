<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'teacher',
            'name' => 'Teacher Bin Example',
            'email' => null,
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);
        
        // Also create a test student
        User::create([
            'username' => 'student',
            'name' => 'Student Bin Student',
            'email' => null,
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}