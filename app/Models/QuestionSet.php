<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionSet extends Model
{
    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'difficulty',
        'operation_type',
        'number_of_questions',
        'time_limit',
        'is_public',
        'is_active',
        'min_value',
        'max_value'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'number_of_questions' => 'integer'
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(QuestionSetAssignment::class);
    }

    public function studentAttempts(): HasMany
    {
        return $this->hasMany(StudentAttempt::class);
    }

    // Get students who have completed this set
    public function completedStudents()
    {
        return $this->assignments()->where('status', 'completed')->with('student')->get();
    }

    // Calculate average score
    public function getAverageScoreAttribute()
    {
        return $this->assignments()
            ->where('status', 'completed')
            ->avg('score') ?? 0;
    }
}