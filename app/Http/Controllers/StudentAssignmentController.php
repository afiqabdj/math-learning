<?php

namespace App\Http\Controllers;

use App\Models\QuestionSet;
use App\Models\QuestionSetAssignment;
use App\Models\StudentAttempt;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentAssignmentController extends Controller
{
    /**
     * Show all assignments for the logged-in student.
     */
    public function index()
    {
        $assignments = QuestionSetAssignment::where('student_id', Auth::id())
            ->with('questionSet')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get available public question sets (not yet assigned to this student)
        $availableSets = QuestionSet::where('is_public', true)
            ->where('is_active', true)
            ->whereDoesntHave('assignments', function ($query) {
                $query->where('student_id', Auth::id());
            })
            ->get();

        return view('student.assignments.index', compact('assignments', 'availableSets'));
    }

    /**
     * Show a specific assignment (take the test).
     */
    public function show($assignmentId)
    {
        $assignment = QuestionSetAssignment::where('id', $assignmentId)
            ->where('student_id', Auth::id())
            ->with('questionSet.questions')
            ->firstOrFail();

        // Check if already completed
        if ($assignment->status === 'completed') {
            return redirect()->route('student.assignments.results', $assignmentId)
                ->with('info', 'You have already completed this assignment.');
        }

        // If not started, set start time
        if (!$assignment->started_at) {
            $assignment->started_at = now();
            $assignment->status = 'in_progress';
            $assignment->save();
        }

        // Get current question index (where the student left off)
        $currentIndex = $assignment->current_question_index ?? 0;
        $totalQuestions = $assignment->questionSet->questions->count();

        // If current index is out of bounds, reset to 0
        if ($currentIndex >= $totalQuestions) {
            $currentIndex = 0;
        }

        // Calculate remaining time
        $timeLimit = $assignment->questionSet->time_limit ?? 0;
        $timeSpent = $assignment->time_spent ?? 0;
        $timeRemaining = max(0, $timeLimit - $timeSpent);

        return view('student.assignments.take', compact('assignment', 'currentIndex', 'timeRemaining', 'timeSpent'));
    }

    /**
     * Submit an answer for a question.
     */
    public function submitAnswer(Request $request)
    {
        try {
            Log::info('Submit answer request:', $request->all());

            $request->validate([
                'assignment_id' => 'required|exists:question_set_assignments,id',
                'question_id' => 'required|exists:questions,id',
                'student_answer' => 'required|integer',
                'time_taken' => 'nullable|integer',
                'current_index' => 'nullable|integer',
            ]);

            $assignment = QuestionSetAssignment::where('id', $request->assignment_id)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            if ($assignment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'This assignment is already completed.'
                ], 403);
            }

            $question = Question::findOrFail($request->question_id);

            // Check if already attempted
            $existingAttempt = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->where('question_id', $request->question_id)
                ->first();

            if ($existingAttempt) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already answered this question.',
                    'already_attempted' => true
                ], 400);
            }

            // Check if answer is correct
            $isCorrect = $question->checkAnswer($request->student_answer);

            // Get correct answer for display
            $correctAnswer = $question->type === 'missing_number'
                ? $question->getMissingNumber()
                : $question->answer;

            // Save the attempt
            StudentAttempt::create([
                'student_id' => Auth::id(),
                'question_set_id' => $assignment->question_set_id,
                'question_id' => $request->question_id,
                'student_answer' => $request->student_answer,
                'is_correct' => $isCorrect,
                'time_taken' => $request->time_taken ?? null,
                'attempt_number' => 1,
            ]);

            // Update assignment - save current position and time spent
            $timeSpent = $assignment->time_spent ?? 0;
            $timeSpent += $request->time_taken ?? 0;

            $assignment->update([
                'current_question_index' => $request->current_index ?? 0,
                'time_spent' => $timeSpent,
                'total_attempts' => ($assignment->total_attempts ?? 0) + 1,
            ]);

            return response()->json([
                'success' => true,
                'is_correct' => $isCorrect,
                'correct_answer' => $correctAnswer,
                'message' => $isCorrect ? '✅ Correct!' : '❌ Incorrect.'
            ]);
        } catch (\Exception $e) {
            Log::error('Submit answer error: ' . $e->getMessage());
            Log::error('Submit answer trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete the assignment and calculate the score.
     */
    public function complete(Request $request, $assignmentId)
    {
        try {
            Log::info('Complete assignment request for ID: ' . $assignmentId);

            $assignment = QuestionSetAssignment::where('id', $assignmentId)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            if ($assignment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment already completed.'
                ], 403);
            }

            // Get all attempts
            $attempts = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->get();

            $totalQuestions = $assignment->questionSet->questions->count();

            // Count unique correct questions
            $correctQuestionIds = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->where('is_correct', true)
                ->pluck('question_id')
                ->unique()
                ->toArray();

            $correctQuestions = count($correctQuestionIds);
            $score = $totalQuestions > 0 ? round(($correctQuestions / $totalQuestions) * 100) : 0;

            // Calculate total time taken
            $timeTaken = $assignment->time_spent ?? 0;

            $assignment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'score' => $score,
                'time_taken' => $timeTaken,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Assignment completed!',
                'score' => $score,
                'correct' => $correctQuestions,
                'total' => $totalQuestions,
                'time_taken' => $this->formatTime($timeTaken),
            ]);
        } catch (\Exception $e) {
            Log::error('Complete assignment error: ' . $e->getMessage());
            Log::error('Complete assignment trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all attempts for this assignment.
     */
    public function getAttempts($assignmentId)
    {
        try {
            $assignment = QuestionSetAssignment::where('id', $assignmentId)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            $attempts = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->select('question_id', 'is_correct', 'student_answer')
                ->get();

            return response()->json([
                'success' => true,
                'attempts' => $attempts
            ]);
        } catch (\Exception $e) {
            Log::error('Get attempts error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current progress (for AJAX refresh).
     */
    public function getProgress($assignmentId)
    {
        try {
            $assignment = QuestionSetAssignment::where('id', $assignmentId)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            $timeLimit = $assignment->questionSet->time_limit ?? 0;
            $timeRemaining = max(0, $timeLimit - ($assignment->time_spent ?? 0));

            return response()->json([
                'success' => true,
                'current_question_index' => $assignment->current_question_index ?? 0,
                'time_spent' => $assignment->time_spent ?? 0,
                'time_remaining' => $timeRemaining,
                'status' => $assignment->status,
                'is_expired' => $timeRemaining <= 0 && $timeLimit > 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show results page for a completed assignment.
     */
    public function results($assignmentId)
    {
        $assignment = QuestionSetAssignment::where('id', $assignmentId)
            ->where('student_id', Auth::id())
            ->with('questionSet.questions')
            ->firstOrFail();

        $attempts = StudentAttempt::where('student_id', Auth::id())
            ->where('question_set_id', $assignment->question_set_id)
            ->with('question')
            ->get()
            ->groupBy('question_id');

        $totalQuestions = $assignment->questionSet->questions->count();
        $correctQuestions = StudentAttempt::where('student_id', Auth::id())
            ->where('question_set_id', $assignment->question_set_id)
            ->where('is_correct', true)
            ->pluck('question_id')
            ->unique()
            ->count();

        $score = $totalQuestions > 0 ? round(($correctQuestions / $totalQuestions) * 100) : 0;

        return view('student.assignments.results', compact('assignment', 'attempts', 'totalQuestions', 'correctQuestions', 'score'));
    }

    /**
     * Enroll in a public question set.
     */
    public function enroll(Request $request)
    {
        try {
            $request->validate([
                'question_set_id' => 'required|exists:question_sets,id'
            ]);

            $existing = QuestionSetAssignment::where('student_id', Auth::id())
                ->where('question_set_id', $request->question_set_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already enrolled in this question set.'
                ], 403);
            }

            $assignment = QuestionSetAssignment::create([
                'question_set_id' => $request->question_set_id,
                'student_id' => Auth::id(),
                'status' => 'assigned',
                'assigned_at' => now(),
                'current_question_index' => 0,
                'time_spent' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enrolled successfully!',
                'assignment_id' => $assignment->id
            ]);
        } catch (\Exception $e) {
            Log::error('Enroll error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to format time.
     */
    private function formatTime($seconds)
    {
        if (!$seconds || $seconds <= 0) {
            return 'N/A';
        }
        $minutes = floor($seconds / 60);
        $secs = $seconds % 60;
        if ($minutes > 0) {
            return $minutes . 'm ' . $secs . 's';
        }
        return $secs . 's';
    }

    /**
     * Save current progress (time spent and current question).
     */
    public function saveProgress(Request $request, $assignmentId)
    {
        try {
            $request->validate([
                'time_spent' => 'required|integer',
                'current_question_index' => 'required|integer',
            ]);

            $assignment = QuestionSetAssignment::where('id', $assignmentId)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            $assignment->update([
                'time_spent' => $request->time_spent,
                'current_question_index' => $request->current_question_index,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Progress saved successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Save progress error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
