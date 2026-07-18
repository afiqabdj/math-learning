<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Level;
use App\Models\UserLevelProgress;
use App\Models\UserStat;
use Illuminate\Support\Facades\Auth;

class ProgressHelper
{
    /**
     * Get or create user progress for a specific level
     */
    public static function getUserLevelProgress($userId, $levelId)
    {
        return UserLevelProgress::firstOrCreate(
            ['user_id' => $userId, 'level_id' => $levelId],
            [
                'cycle1_completed' => false,
                'cycle1_best_time' => null,
                'cycle1_attempts' => 0,
                'cycle1_last_score' => 0,
                'cycle2_completed' => false,
                'cycle2_best_marks' => 0,
                'cycle2_attempts' => 0,
                'cycle2_last_score' => 0,
                'stars_earned' => 0,
            ]
        );
    }
    
    /**
     * Update Cycle 1 (Practice Mode) progress
     */
    public static function updateCycle1Progress($userId, $levelId, $score, $timeSpent, $accuracy)
    {
        $progress = self::getUserLevelProgress($userId, $levelId);
        
        // Update attempts
        $progress->cycle1_attempts++;
        $progress->cycle1_last_score = $score;
        
        // Update best time if this attempt is faster
        if ($progress->cycle1_best_time === null || $timeSpent < $progress->cycle1_best_time) {
            $progress->cycle1_best_time = $timeSpent;
        }
        
        // Check if level is completed (if score meets target)
        $level = Level::where('level_number', $levelId)->first();
        $targetScore = $level ? $level->target_score : 100;
        
        if ($score >= $targetScore) {
            $progress->cycle1_completed = true;
            
            // Update stars earned (10 stars per question, 10 questions = 100 max)
            $progress->stars_earned = max($progress->stars_earned, $score);
        }
        
        $progress->save();
        
        // Update overall user stats
        self::updateUserStats($userId);
        
        return $progress;
    }
    
    /**
     * Update Cycle 2 (Timed Challenge) progress
     */
    public static function updateCycle2Progress($userId, $levelId, $score, $accuracy, $timeRemaining)
    {
        $progress = self::getUserLevelProgress($userId, $levelId);
        
        // Update attempts
        $progress->cycle2_attempts++;
        $progress->cycle2_last_score = $score;
        
        // Update best marks if this attempt is better
        if ($accuracy > $progress->cycle2_best_marks) {
            $progress->cycle2_best_marks = $accuracy;
        }
        
        // Check if level is completed (if score meets target)
        $level = Level::where('level_number', $levelId)->first();
        $targetScore = $level ? $level->target_score : 100;
        
        if ($score >= $targetScore) {
            $progress->cycle2_completed = true;
            $progress->completed_at = now();
            
            // Update stars earned (15 stars per question in cycle 2)
            $starsEarned = $score;
            $progress->stars_earned = max($progress->stars_earned, $starsEarned);
        }
        
        $progress->save();
        
        // Update overall user stats
        self::updateUserStats($userId);
        
        return $progress;
    }
    
    /**
     * Update overall user statistics
     */
    public static function updateUserStats($userId)
    {
        // Get all user progress
        $allProgress = UserLevelProgress::where('user_id', $userId)->get();
        
        // Calculate total stars
        $totalStars = $allProgress->sum('stars_earned');
        
        // Calculate completed levels (both cycles completed)
        $completedLevels = $allProgress->filter(function ($progress) {
            return $progress->cycle1_completed && $progress->cycle2_completed;
        })->count();
        
        // Find current level (first incomplete level)
        $currentLevel = 1;
        for ($i = 1; $i <= 20; $i++) {
            $levelProgress = $allProgress->firstWhere('level_id', $i);
            if (!$levelProgress || !$levelProgress->cycle1_completed || !$levelProgress->cycle2_completed) {
                $currentLevel = $i;
                break;
            }
        }
        
        // Update or create user stats
        UserStat::updateOrCreate(
            ['user_id' => $userId],
            [
                'total_stars' => $totalStars,
                'total_levels_completed' => $completedLevels,
                'current_level' => $currentLevel,
            ]
        );
    }
    
    /**
     * Get overall progress for dashboard
     */
    public static function getDashboardStats($userId)
    {
        $stats = UserStat::where('user_id', $userId)->first();
        $allProgress = UserLevelProgress::where('user_id', $userId)->get();
        
        // Calculate average accuracy across completed levels
        $totalAccuracy = 0;
        $accuracyCount = 0;
        
        foreach ($allProgress as $progress) {
            if ($progress->cycle2_best_marks > 0) {
                $totalAccuracy += $progress->cycle2_best_marks;
                $accuracyCount++;
            }
        }
        
        $averageAccuracy = $accuracyCount > 0 ? round($totalAccuracy / $accuracyCount) : 0;
        
        // Find best level (highest stars)
        $bestLevel = $allProgress->sortByDesc('stars_earned')->first();
        
        // Find fastest time
        $fastestTime = $allProgress->filter(function ($progress) {
            return $progress->cycle1_best_time !== null;
        })->sortBy('cycle1_best_time')->first();
        
        return [
            'total_stars' => $stats->total_stars ?? 0,
            'completed_levels' => $stats->total_levels_completed ?? 0,
            'current_level' => $stats->current_level ?? 1,
            'average_accuracy' => $averageAccuracy,
            'best_level' => $bestLevel ? $bestLevel->level_id : null,
            'best_stars' => $bestLevel ? $bestLevel->stars_earned : 0,
            'fastest_time' => $fastestTime ? $fastestTime->cycle1_best_time : null,
            'fastest_time_level' => $fastestTime ? $fastestTime->level_id : null,
        ];
    }
    
    /**
     * Get detailed level progress for a specific user
     */
    public static function getUserLevelDetails($userId, $levelId)
    {
        $progress = self::getUserLevelProgress($userId, $levelId);
        $level = Level::where('level_number', $levelId)->first();
        
        return [
            'level_number' => $levelId,
            'level_name' => $level ? $level->level_name : "Level $levelId",
            'target_score' => $level ? $level->target_score : 100,
            
            // Cycle 1 info
            'cycle1_completed' => $progress->cycle1_completed,
            'cycle1_best_time' => $progress->cycle1_best_time,
            'cycle1_attempts' => $progress->cycle1_attempts,
            'cycle1_last_score' => $progress->cycle1_last_score,
            
            // Cycle 2 info
            'cycle2_completed' => $progress->cycle2_completed,
            'cycle2_best_marks' => $progress->cycle2_best_marks,
            'cycle2_attempts' => $progress->cycle2_attempts,
            'cycle2_last_score' => $progress->cycle2_last_score,
            
            // Overall
            'stars_earned' => $progress->stars_earned,
            'completed_at' => $progress->completed_at,
        ];
    }
    
    /**
     * Get leaderboard data (for future use)
     */
    public static function getLeaderboard($limit = 10)
    {
        return UserStat::with('user')
            ->orderBy('total_stars', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($stat) {
                return [
                    'username' => $stat->user->username,
                    'total_stars' => $stat->total_stars,
                    'levels_completed' => $stat->total_levels_completed,
                ];
            });
    }
}