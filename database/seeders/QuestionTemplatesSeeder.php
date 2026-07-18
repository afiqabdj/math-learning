<?php

namespace Database\Seeders;

use App\Models\QuestionTemplate;
use Illuminate\Database\Seeder;

class QuestionTemplatesSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Basic Addition - Two Numbers',
                'description' => 'Simple addition with two numbers',
                'type' => 'two_numbers',
                'min_value' => 0,
                'max_value' => 20,
                'default_points' => 1,
            ],
            [
                'name' => 'Advanced Addition - Three Numbers',
                'description' => 'Addition with three numbers',
                'type' => 'three_numbers',
                'min_value' => 0,
                'max_value' => 15,
                'default_points' => 2,
            ],
            [
                'name' => 'Find the Missing Number',
                'description' => 'Find the missing number in the equation',
                'type' => 'missing_number',
                'min_value' => 0,
                'max_value' => 20,
                'default_points' => 3,
            ],
        ];

        foreach ($templates as $template) {
            QuestionTemplate::create($template);
        }
    }
}