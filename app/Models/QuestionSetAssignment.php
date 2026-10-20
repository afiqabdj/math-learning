<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionSetAssignment extends Model
{
    protected $fillable = [
        'question_set_id',
        'student_id',
        'status',
        'score',
        'assigned_at',
        'started_at',        // When student started
        'current_question_index', // Current question position
        'time_spent',        // Total time spent in seconds
        'completed_at',
        'time_taken',
        'total_attempts',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(StudentAttempt::class);
    }
}