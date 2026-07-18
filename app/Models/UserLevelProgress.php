<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLevelProgress extends Model
{
    protected $table = 'user_level_progress';
    
    protected $fillable = [
        'user_id',
        'level_id',
        'cycle1_completed',
        'cycle1_best_time',
        'cycle1_attempts',
        'cycle1_last_score',
        'cycle2_completed',
        'cycle2_best_marks',
        'cycle2_attempts',
        'cycle2_last_score',
        'stars_earned',
        'completed_at',
    ];
    
    protected $casts = [
        'cycle1_completed' => 'boolean',
        'cycle2_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}