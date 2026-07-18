<?php

namespace App\Livewire;

use Livewire\Component;

class MathGame extends Component
{
    public $score = 0;
    public $lives = 3;
    public $currentQuestion = 1;
    
    // Lesson settings
    public $lessonId = 1; // 1 = Lesson 1 (addition to 10), 2 = Lesson 2 (missing number)
    public $lessonName = '';
    public $totalQuestions = 5;
    
    // Question parts
    public $num1;
    public $num2;
    public $missingNumber = false;
    public $options = [];
    public $correctAnswer;
    
    public $message = '';
    public $messageType = '';
    public $gameOver = false;
    public $lessonComplete = false;

    public function mount()
    {
        $this->loadLesson();
        $this->generateQuestion();
    }
    
    public function loadLesson()
    {
        if ($this->lessonId == 1) {
            $this->lessonName = 'Lesson 1: Numbers that make 10';
            $this->totalQuestions = 5;
            $this->missingNumber = false;
        } elseif ($this->lessonId == 2) {
            $this->lessonName = 'Lesson 2: Find the missing number';
            $this->totalQuestions = 5;
            $this->missingNumber = true;
        }
    }
    
    public function generateQuestion()
    {
        if ($this->lessonId == 1) {
            // Lesson 1: Simple addition to 10
            $pairs = [
                [1, 9], [2, 8], [3, 7], [4, 6], [5, 5],
                [6, 4], [7, 3], [8, 2], [9, 1], [10, 0]
            ];
            
            // Pick a random pair
            $randomIndex = array_rand($pairs);
            $this->num1 = $pairs[$randomIndex][0];
            $this->num2 = $pairs[$randomIndex][1];
            $this->correctAnswer = 10;
            
        } elseif ($this->lessonId == 2) {
            // Lesson 2: Find missing number to make 10
            $pairs = [
                ['missing' => 1, 'given' => 9],
                ['missing' => 2, 'given' => 8],
                ['missing' => 3, 'given' => 7],
                ['missing' => 4, 'given' => 6],
                ['missing' => 5, 'given' => 5],
                ['missing' => 6, 'given' => 4],
                ['missing' => 7, 'given' => 3],
                ['missing' => 8, 'given' => 2],
                ['missing' => 9, 'given' => 1]
            ];
            
            $randomIndex = array_rand($pairs);
            $this->num1 = $pairs[$randomIndex]['given'];
            $this->num2 = null; // Not used
            $this->correctAnswer = $pairs[$randomIndex]['missing'];
        }
        
        $this->generateOptions();
    }
    
    public function generateOptions()
    {
        if ($this->lessonId == 1) {
            // Options for "? + ? = 10" - show possible sums
            $wrongOptions = [8, 9, 11, 12];
            $this->options = array_merge([$this->correctAnswer], $wrongOptions);
        } else {
            // Options for missing number - numbers 0-10
            $allNumbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            // Remove correct answer and pick 3 random wrong ones
            $possibleWrong = array_diff($allNumbers, [$this->correctAnswer]);
            $randomWrong = array_rand(array_flip($possibleWrong), 3);
            $this->options = array_merge([$this->correctAnswer], $randomWrong);
        }
        
        shuffle($this->options);
    }
    
    public function checkAnswer($answer)
    {
        if ($answer == $this->correctAnswer) {
            $this->score += 10;
            $this->message = '✅ Great job! +10 stars';
            $this->messageType = 'success';
            
            if ($this->currentQuestion >= $this->totalQuestions) {
                $this->lessonComplete = true;
                $this->message = '🎉 Lesson Complete! You got ' . $this->score . ' stars!';
            } else {
                $this->currentQuestion++;
                $this->generateQuestion();
            }
        } else {
            $this->lives--;
            $this->message = '❌ Try again! You can do it!';
            $this->messageType = 'error';
            
            if ($this->lives <= 0) {
                $this->message = 'Good try! Let\'s practice more!';
                $this->messageType = 'gameover';
                $this->gameOver = true;
            }
        }
    }
    
    public function nextLesson()
    {
        $this->lessonId++;
        $this->resetGame(); // Changed from reset() to resetGame()
        $this->loadLesson();
        $this->generateQuestion();
    }
    
    // Renamed from reset() to resetGame() to avoid conflict with Livewire's reset()
    public function resetGame()
    {
        $this->score = 0;
        $this->lives = 3;
        $this->currentQuestion = 1;
        $this->gameOver = false;
        $this->lessonComplete = false;
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.math-game');
    }
}