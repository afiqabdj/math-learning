<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStat extends Model
{
    protected $table = 'user_stats';
    
    protected $fillable = [
        'user_id',
        'total_stars',
        'total_levels_completed',
        'current_level',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}