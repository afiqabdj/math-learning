<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameResult extends Model
{
    protected $fillable = [
        'user_id',
        'level_number',
        'cycle',
        'score',
        'accuracy',
        'time_taken',
        'is_completed',
    ];
    
    protected $casts = [
        'is_completed' => 'boolean',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}