<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'lesson_number',
        'lesson_name',
        'lesson_icon',
        'description',
        'total_questions',
    ];
    
    public function userProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }
}