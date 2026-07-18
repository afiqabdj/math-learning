<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'level_number',
        'level_name',
        'target_score',
        'questions_per_level',
    ];
    
    public function userProgress(): HasMany
    {
        return $this->hasMany(UserLevelProgress::class);
    }
}