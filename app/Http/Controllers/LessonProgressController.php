<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LessonProgress;

class LessonProgressController extends Controller
{
    // Get all lesson progress for the logged-in user
    public function getProgress()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $progress = LessonProgress::where('user_id', $user->id)->get();
        $completedLessons = $progress->where('is_completed', true)->pluck('lesson_number')->toArray();

        return response()->json([
            'completed_lessons' => $completedLessons,
        ]);
    }

    // Mark a lesson as completed
    public function completeLesson(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $request->validate([
            'lesson_number' => 'required|integer|min:1|max:11',
        ]);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_number' => $request->lesson_number,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Lesson completed!',
            'data' => $progress
        ]);
    }
}
