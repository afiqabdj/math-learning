<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle2Result extends Model
{
    protected $table = 'cycle2_results';
    
    protected $fillable = [
        'user_id',
        'level_number',
        'score',
        'accuracy',
        'time_remaining',
        'correct_answers',
        'is_completed',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}