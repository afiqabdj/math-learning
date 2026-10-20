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

    /**
     * Get the operation type from the parent question set.
     */
    protected function getOperation(): string
    {
        return $this->questionSet?->operation_type ?? 'addition';
    }

    /**
     * Get the separator based on operation type.
     */
    protected function getSeparator(): string
    {
        return $this->getOperation() === 'subtraction' ? ' - ' : ' + ';
    }

    /**
     * Get the correct answer for this question.
     * For missing_number type, this returns the missing number.
     * For other types, this returns the equation result.
     */
    public function getCorrectAnswer(): int
    {
        if ($this->type === 'missing_number') {
            $missingNumber = $this->getMissingNumber();
            return $missingNumber ?? $this->answer;
        }
        return $this->answer;
    }

    /**
     * Get the missing number (the actual answer the student needs to enter).
     */
    public function getMissingNumber(): ?int
    {
        if ($this->type !== 'missing_number' || $this->missing_position === null) {
            return null;
        }
        
        $parts = [$this->num1, $this->num2, $this->num3];
        return $parts[$this->missing_position - 1] ?? null;
    }

    /**
     * Display question with proper operation sign.
     */
    public function getDisplayText(): string
    {
        $separator = $this->getSeparator();

        switch ($this->type) {
            case 'two_numbers':
                return "{$this->num1}{$separator}{$this->num2} = ⬜";
            case 'three_numbers':
                return "{$this->num1}{$separator}{$this->num2}{$separator}{$this->num3} = ⬜";
            case 'missing_number':
                $parts = [$this->num1, $this->num2, $this->num3];
                $displayParts = $parts;
                $displayParts[$this->missing_position - 1] = '⬜';
                return implode($separator, $displayParts) . " = " . $this->answer;
            default:
                return "{$this->num1}{$separator}{$this->num2} = ⬜";
        }
    }

    /**
     * Display question for student with proper operation sign.
     */
    public function getDisplayForStudent(): string
    {
        return $this->getDisplayText();
    }

    /**
     * Display question with the blank (for student answering).
     */
    public function getDisplayWithBlank(): string
    {
        $separator = $this->getSeparator();

        switch ($this->type) {
            case 'two_numbers':
                return "{$this->num1}{$separator}{$this->num2} = ___";
            case 'three_numbers':
                return "{$this->num1}{$separator}{$this->num2}{$separator}{$this->num3} = ___";
            case 'missing_number':
                $parts = [$this->num1, $this->num2, $this->num3];
                $displayParts = $parts;
                $displayParts[$this->missing_position - 1] = '___';
                return implode($separator, $displayParts) . " = " . $this->answer;
            default:
                return "{$this->num1}{$separator}{$this->num2} = ___";
        }
    }

    /**
     * Check if student's answer is correct.
     * For two_numbers and three_numbers: compares student answer with the equation result (answer field)
     * For missing_number: compares student answer with the missing number
     */
    public function checkAnswer(int $studentAnswer): bool
    {
        // For missing_number type, the answer is the missing number
        if ($this->type === 'missing_number') {
            $missingNumber = $this->getMissingNumber();
            return $studentAnswer === $missingNumber;
        }
        
        // For two_numbers and three_numbers, the answer is the equation result
        return $studentAnswer === $this->answer;
    }
}