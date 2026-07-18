<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [1, 'Making 10', 100, 10],
            [2, 'Addition Practice', 100, 10],
            [3, 'Making 10 & More', 100, 10],
            [4, 'Adding Tens', 100, 10],
            [5, 'Adding Up to 20', 100, 10],
            [6, 'Missing Number (Beginning)', 100, 10],
            [7, 'Missing Number (End)', 100, 10],
            [8, 'Missing Number - Mixed', 100, 10],
            [9, 'Teen Numbers - Missing End', 100, 10],
            [10, 'Teen Numbers - Missing Beginning', 100, 10],
            [11, 'Missing Number (1-9)', 100, 10],
            [12, 'Missing Number (10-19)', 100, 10],
            [13, 'Mixed Teen Sums', 100, 10],
            [14, 'Ultimate Teen Review', 100, 10],
            [15, 'Mega Review', 100, 10],
            [16, 'Addition (10-20)', 100, 10],
            [17, 'Addition (15-25)', 100, 10],
            [18, 'Addition (20-30)', 100, 10],
            [19, 'Addition (25-35)', 100, 10],
            [20, 'Addition (40-50)', 100, 10],
        ];

        foreach ($levels as $level) {
            DB::table('levels')->insert([
                'level_number' => $level[0],
                'level_name' => $level[1],
                'target_score' => $level[2],
                'questions_per_level' => $level[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}