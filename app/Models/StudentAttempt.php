<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'question_set_id',
        'question_id',
        'student_answer',
        'is_correct',
        'time_taken',
        'attempt_number'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}