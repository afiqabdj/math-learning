<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionSetAssignment extends Model
{
    protected $fillable = [
        'question_set_id',
        'student_id',
        'status',
        'assigned_at',
        'completed_at',
        'score',
        'total_attempts'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}