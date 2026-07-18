<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = [
            [1, 'Numbers 0-9', '🔢', 'Learn to add numbers from 0 to 9', 20],
            [2, 'Numbers 0-10', '🔟', 'Learn to add numbers up to 10', 20],
            [3, 'Three Numbers Make 10', '🧮', 'Learn how three numbers can add up to 10', 20],
            [4, 'Numbers 10-19 (Teen Numbers)', '🔟', 'Learn numbers from 11 to 19', 20],
            [5, 'Subtraction (Below 10)', '➖', 'Learn to subtract numbers below 10', 20],
            [6, 'Bridging to 10 Technique', '🎯', 'Learn the secret trick to add numbers faster', 50],
            [7, 'Adding Teen Numbers', '🔟', 'Learn to add teen numbers (10-19) + numbers up to 10', 56],
            [8, 'Teen + Teen Numbers', '🔟🔟', 'Learn to add teen numbers to teen numbers', 56],
            [9, 'Adding Two-Digit Numbers', '💪', 'Learn to add bigger two-digit numbers', 56],
            [10, 'Subtracting Same Tens', '➖➖', 'Learn to subtract numbers with the same tens', 35],
        ];

        foreach ($lessons as $lesson) {
            DB::table('lessons')->insert([
                'lesson_number' => $lesson[0],
                'lesson_name' => $lesson[1],
                'lesson_icon' => $lesson[2],
                'description' => $lesson[3],
                'total_questions' => $lesson[4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}