<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle1Result extends Model
{
    protected $table = 'cycle1_results';
    
    protected $fillable = [
        'user_id',
        'level_number',
        'questions_answered',
        'time_limit',
        'correct_answers',
        'wrong_answers',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}