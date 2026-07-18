<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users_congak';

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    // Helper methods for role checking
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }
    
    public function isStudent()
    {
        return $this->role === 'student';
    }
    
    // Relationships
    public function cycle1Results()
    {
        return $this->hasMany(Cycle1Result::class);
    }
    
    public function cycle2Results()
    {
        return $this->hasMany(Cycle2Result::class);
    }
    
    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Get overall lesson completion percentage
    public function getOverallLessonCompletionAttribute()
    {
        $totalLessons = \App\Models\Lesson::count();
        $completedLessons = $this->lessonProgress()->where('is_completed', true)->count();
        
        if ($totalLessons <= 0) return 0;
        return round(($completedLessons / $totalLessons) * 100);
    }
    
    // Get total completed lessons count
    public function getCompletedLessonsCountAttribute()
    {
        return $this->lessonProgress()->where('is_completed', true)->count();
    }
}