<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\UserLevelProgress;
use App\Models\Level;
use App\Models\UserStat;

class GameProgressController extends Controller
{
    // Save Cycle 1 (Practice Mode) results
    public function saveCycle1(Request $request)
    {
        Log::info('Cycle 1 save request received', $request->all());
        
        $request->validate([
            'level_id' => 'required|integer',
            'score' => 'required|integer',
            'accuracy' => 'required|integer',
            'time_spent' => 'required|integer',
        ]);

        $user = Auth::user();
        if (!$user) {
            Log::error('No authenticated user');
            return response()->json(['success' => false, 'message' => 'User not authenticated']);
        }
        
        $levelNumber = $request->level_id;
        
        // Find level by level_number (NOT by id)
        $level = Level::where('level_number', $levelNumber)->first();
        if (!$level) {
            Log::error('Level not found for level_number: ' . $levelNumber);
            return response()->json(['success' => false, 'message' => 'Level not found']);
        }
        
        Log::info('Found level', ['level_id' => $level->id, 'level_number' => $level->level_number]);
        
        // Get or create progress record
        $progress = UserLevelProgress::firstOrCreate(
            ['user_id' => $user->id, 'level_id' => $level->id],
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
        
        // Update Cycle 1 progress
        $progress->cycle1_attempts = ($progress->cycle1_attempts ?? 0) + 1;
        $progress->cycle1_last_score = $request->score;
        
        // Update best time if this attempt is faster
        if ($progress->cycle1_best_time === null || $request->time_spent < $progress->cycle1_best_time) {
            $progress->cycle1_best_time = $request->time_spent;
        }
        
        // Mark Cycle 1 as completed
        $progress->cycle1_completed = true;
        $progress->stars_earned = max($progress->stars_earned ?? 0, $request->score);
        
        $progress->save();
        
        Log::info('Cycle 1 saved', [
            'user_id' => $user->id,
            'level_id' => $level->id,
            'attempts' => $progress->cycle1_attempts,
            'score' => $request->score,
            'time_spent' => $request->time_spent
        ]);
        
        // Update user stats
        $this->updateUserStats($user->id);
        
        return response()->json(['success' => true]);
    }
    
    // Save Cycle 2 (Timed Challenge) results
    public function saveCycle2(Request $request)
    {
        Log::info('Cycle 2 save request received', $request->all());
        
        $request->validate([
            'level_id' => 'required|integer',
            'score' => 'required|integer',
            'accuracy' => 'required|integer',
            'time_remaining' => 'required|integer',
        ]);

        $user = Auth::user();
        if (!$user) {
            Log::error('No authenticated user');
            return response()->json(['success' => false, 'message' => 'User not authenticated']);
        }
        
        $levelNumber = $request->level_id;
        
        // Find level by level_number
        $level = Level::where('level_number', $levelNumber)->first();
        if (!$level) {
            Log::error('Level not found for level_number: ' . $levelNumber);
            return response()->json(['success' => false, 'message' => 'Level not found']);
        }
        
        // Get or create progress record
        $progress = UserLevelProgress::firstOrCreate(
            ['user_id' => $user->id, 'level_id' => $level->id],
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
        
        // Update Cycle 2 progress
        $progress->cycle2_attempts = ($progress->cycle2_attempts ?? 0) + 1;
        $progress->cycle2_last_score = $request->score;
        
        // Update best marks if this attempt is better
        if ($request->accuracy > ($progress->cycle2_best_marks ?? 0)) {
            $progress->cycle2_best_marks = $request->accuracy;
        }
        
        // Mark Cycle 2 as completed
        $progress->cycle2_completed = true;
        $progress->stars_earned = max($progress->stars_earned ?? 0, $request->score);
        $progress->completed_at = now();
        
        $progress->save();
        
        Log::info('Cycle 2 saved', [
            'user_id' => $user->id,
            'level_id' => $level->id,
            'attempts' => $progress->cycle2_attempts,
            'score' => $request->score,
            'accuracy' => $request->accuracy,
            'time_remaining' => $request->time_remaining
        ]);
        
        // Update user stats
        $this->updateUserStats($user->id);
        
        return response()->json(['success' => true]);
    }
    
    // Get user progress for dashboard
    public function getUserProgress()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $stats = UserStat::where('user_id', $user->id)->first();
        
        if (!$stats) {
            $stats = UserStat::create([
                'user_id' => $user->id,
                'total_stars' => 0,
                'total_levels_completed' => 0,
                'current_level' => 1,
                'total_lessons_completed' => 0,
                'current_lesson' => 1,
            ]);
        }
        
        return response()->json([
            'total_stars' => $stats->total_stars,
            'total_levels_completed' => $stats->total_levels_completed,
            'current_level' => $stats->current_level,
        ]);
    }
    
    // Get all levels progress
    public function getAllLevelsProgress()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $allProgress = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $level = Level::where('level_number', $i)->first();
            if ($level) {
                $progress = UserLevelProgress::where('user_id', $user->id)
                    ->where('level_id', $level->id)
                    ->first();
                
                $allProgress[] = [
                    'level_number' => $i,
                    'cycle1_completed' => $progress ? (bool)$progress->cycle1_completed : false,
                    'cycle2_completed' => $progress ? (bool)$progress->cycle2_completed : false,
                    'cycle2_best_marks' => $progress ? (int)($progress->cycle2_best_marks ?? 0) : 0,
                    'cycle1_best_time' => $progress ? $progress->cycle1_best_time : null,
                    'stars_earned' => $progress ? (int)($progress->stars_earned ?? 0) : 0,
                ];
            } else {
                $allProgress[] = [
                    'level_number' => $i,
                    'cycle1_completed' => false,
                    'cycle2_completed' => false,
                    'cycle2_best_marks' => 0,
                    'cycle1_best_time' => null,
                    'stars_earned' => 0,
                ];
            }
        }
        
        return response()->json($allProgress);
    }
    
    // Update user statistics
    private function updateUserStats($userId)
    {
        $allProgress = UserLevelProgress::where('user_id', $userId)->get();
        
        $totalStars = $allProgress->sum('stars_earned');
        $completedLevels = $allProgress->filter(function ($p) {
            return $p->cycle1_completed && $p->cycle2_completed;
        })->count();
        
        // Find current level (first incomplete)
        $currentLevel = 1;
        for ($i = 1; $i <= 20; $i++) {
            $level = Level::where('level_number', $i)->first();
            if ($level) {
                $progress = $allProgress->firstWhere('level_id', $level->id);
                if (!$progress || !$progress->cycle1_completed || !$progress->cycle2_completed) {
                    $currentLevel = $i;
                    break;
                }
                $currentLevel = $i + 1;
            }
        }
        if ($currentLevel > 20) $currentLevel = 20;
        
        UserStat::updateOrCreate(
            ['user_id' => $userId],
            [
                'total_stars' => $totalStars,
                'total_levels_completed' => $completedLevels,
                'current_level' => $currentLevel,
            ]
        );
    }
}