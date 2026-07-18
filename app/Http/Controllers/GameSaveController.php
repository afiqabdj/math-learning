<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Cycle1Result;
use App\Models\Cycle2Result;
use Illuminate\Support\Facades\DB;

class GameSaveController extends Controller
{
    // Save Cycle 2 results
    public function saveCycle2(Request $request)
    {
        // Log the request for debugging
        Log::info('=== SAVE CYCLE 2 CALLED ===');
        Log::info('Request data:', $request->all());

        $user = Auth::user();

        if (!$user) {
            Log::error('No user authenticated');
            return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
        }

        try {
            $cycle2Result = Cycle2Result::create([
                'user_id' => $user->id,
                'level_number' => $request->level_id,
                'score' => $request->score,
                'accuracy' => $request->accuracy,
                'time_remaining' => $request->time_remaining,
                'correct_answers' => $request->correct_answers,
                'is_completed' => $request->is_completed,
            ]);

            Log::info('Cycle 2 result saved with ID: ' . $cycle2Result->id);

            return response()->json([
                'success' => true,
                'message' => 'Saved successfully',
                'id' => $cycle2Result->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving Cycle 2: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Save Cycle 1 results
    public function saveCycle1(Request $request)
    {
        Log::info('=== SAVE CYCLE 1 CALLED ===');
        Log::info('Request data:', $request->all());

        $user = Auth::user();

        if (!$user) {
            Log::error('No user authenticated');
            return response()->json(['success' => false, 'message' => 'Not logged in'], 401);
        }

        try {
            $cycle1Result = Cycle1Result::create([
                'user_id' => $user->id,
                'level_number' => $request->level_id,
                'questions_answered' => $request->questions_answered,
                'time_limit' => $request->time_limit,
                'correct_answers' => $request->correct_answers,
                'wrong_answers' => $request->wrong_answers,
            ]);

            Log::info('Cycle 1 result saved with ID: ' . $cycle1Result->id);

            return response()->json([
                'success' => true,
                'message' => 'Saved successfully',
                'id' => $cycle1Result->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving Cycle 1: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get Cycle 2 results for a user
    public function getCycle2Results()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $results = Cycle2Result::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($results);
    }

    // Get Cycle 1 results for a user
    public function getCycle1Results()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $results = Cycle1Result::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($results);
    }

    // Get user stats from cycle tables
    public function getUserStats()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        // Calculate total stars from Cycle 2 results (each cycle 2 completion gives stars)
        $totalStars = Cycle2Result::where('user_id', $user->id)->sum('score');

        // Calculate completed levels (levels where both cycles are completed)
        $cycle1Completed = Cycle1Result::where('user_id', $user->id)
            ->select('level_number')
            ->distinct()
            ->get()
            ->pluck('level_number')
            ->toArray();

        $cycle2Completed = Cycle2Result::where('user_id', $user->id)
            ->where('is_completed', true)
            ->select('level_number')
            ->distinct()
            ->get()
            ->pluck('level_number')
            ->toArray();

        $fullyCompletedLevels = array_intersect($cycle1Completed, $cycle2Completed);
        $totalLevelsCompleted = count($fullyCompletedLevels);

        // Current level is the next level to unlock
        $currentLevel = $totalLevelsCompleted + 1;
        if ($currentLevel > 20) $currentLevel = 20;

        return response()->json([
            'total_stars' => $totalStars,
            'total_levels_completed' => $totalLevelsCompleted,
            'current_level' => $currentLevel,
        ]);
    }

    // Get all levels progress for level selection page
    public function getAllLevelsProgress()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Not logged in'], 401);
        }

        $allProgress = [];

        // Get all cycle 1 completions
        $cycle1Completions = Cycle1Result::where('user_id', $user->id)
            ->select('level_number')
            ->distinct()
            ->get()
            ->pluck('level_number')
            ->toArray();

        // Get all cycle 2 completions with best accuracy
        $cycle2Results = Cycle2Result::where('user_id', $user->id)
            ->where('is_completed', true)
            ->get();

        $cycle2BestAccuracy = [];
        foreach ($cycle2Results as $result) {
            $level = $result->level_number;
            if (!isset($cycle2BestAccuracy[$level]) || $result->accuracy > $cycle2BestAccuracy[$level]) {
                $cycle2BestAccuracy[$level] = $result->accuracy;
            }
        }

        for ($i = 1; $i <= 20; $i++) {
            $allProgress[] = [
                'level_number' => $i,
                'cycle1_completed' => in_array($i, $cycle1Completions),
                'cycle2_completed' => isset($cycle2BestAccuracy[$i]),
                'cycle2_best_marks' => $cycle2BestAccuracy[$i] ?? 0,
            ];
        }

        return response()->json($allProgress);
    }

    // Get total number of levels
    public function getTotalLevels()
    {
        $totalLevels = DB::table('levels')->count();
        return response()->json(['total' => $totalLevels]);
    }

    // Get total number of lessons
    public function getTotalLessons()
    {
        $totalLessons = DB::table('lessons')->count();
        return response()->json(['total' => $totalLessons]);
    }

    // Get ALL Cycle 2 results for teacher dashboard (no user filter)
    public function getTeacherCycle2Results()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized. Teacher access required.'], 401);
        }

        // Fix: Apply orderBy before get()
        $results = Cycle2Result::orderBy('created_at', 'desc')->get();
        return response()->json($results);
    }

    // Get ALL Cycle 1 results for teacher dashboard (no user filter)
    public function getTeacherCycle1Results()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized. Teacher access required.'], 401);
        }

        // Fix: Apply orderBy before get()
        $results = Cycle1Result::orderBy('created_at', 'desc')->get();
        return response()->json($results);
    }
}
