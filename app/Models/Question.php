<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'question_set_id',
        'question_number',
        'type',
        'num1',
        'num2',
        'num3',
        'answer',
        'missing_position',
    ];

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function studentAttempts(): HasMany
    {
        return $this->hasMany(StudentAttempt::class);
    }

    // Display question with + sign (always addition)
    public function getDisplayText(): string
    {
        switch ($this->type) {
            case 'two_numbers':
                return "{$this->num1} + {$this->num2} = ?";
            case 'three_numbers':
                return "{$this->num1} + {$this->num2} + {$this->num3} = ?";
            case 'missing_number':
                $parts = [$this->num1, $this->num2, $this->num3];
                $displayParts = $parts;
                $displayParts[$this->missing_position - 1] = '?';
                return implode(' + ', $displayParts) . " = " . $this->answer;
            default:
                return "{$this->num1} + {$this->num2} = ?";
        }
    }

    // Get the missing number (the actual answer the student needs to enter)
    public function getMissingNumber(): ?int
    {
        if ($this->type !== 'missing_number' || $this->missing_position === null) {
            return null;
        }
        
        $parts = [$this->num1, $this->num2, $this->num3];
        return $parts[$this->missing_position - 1] ?? null;
    }

    // Display question for student
    public function getDisplayForStudent(): string
    {
        switch ($this->type) {
            case 'two_numbers':
                return "{$this->num1} + {$this->num2} = ?";
            case 'three_numbers':
                return "{$this->num1} + {$this->num2} + {$this->num3} = ?";
            case 'missing_number':
                $parts = [$this->num1, $this->num2, $this->num3];
                $displayParts = $parts;
                $displayParts[$this->missing_position - 1] = '?';
                return implode(' + ', $displayParts) . " = " . $this->answer;
            default:
                return "{$this->num1} + {$this->num2} = ?";
        }
    }

    // Display question with the blank (for student answering)
    public function getDisplayWithBlank(): string
    {
        switch ($this->type) {
            case 'two_numbers':
                return "{$this->num1} + {$this->num2} = ___";
            case 'three_numbers':
                return "{$this->num1} + {$this->num2} + {$this->num3} = ___";
            case 'missing_number':
                $parts = [$this->num1, $this->num2, $this->num3];
                $displayParts = $parts;
                $displayParts[$this->missing_position - 1] = '___';
                return implode(' + ', $displayParts) . " = " . $this->answer;
            default:
                return "{$this->num1} + {$this->num2} = ___";
        }
    }

    // Check if answer is correct
    public function checkAnswer(int $studentAnswer): bool
    {
        if ($this->type === 'missing_number') {
            $missingNumber = $this->getMissingNumber();
            return $studentAnswer === $missingNumber;
        }
        return $studentAnswer === $this->answer;
    }
}