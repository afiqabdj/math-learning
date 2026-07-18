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
    // Show all assignments for the logged-in student
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

    // Show a specific assignment (take the test)
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

        // Update status to in_progress if not already
        if ($assignment->status === 'assigned') {
            $assignment->status = 'in_progress';
            $assignment->save();
        }

        return view('student.assignments.take', compact('assignment'));
    }

    // Submit an answer
    public function submitAnswer(Request $request)
    {
        try {
            $request->validate([
                'assignment_id' => 'required|exists:question_set_assignments,id',
                'question_id' => 'required|exists:questions,id',
                'student_answer' => 'required|integer',
                'time_taken' => 'nullable|integer',
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

            // Check if the student has already attempted this question
            $existingAttempt = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->where('question_id', $request->question_id)
                ->first();

            // If already attempted, don't allow another attempt
            if ($existingAttempt) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already answered this question.',
                    'already_attempted' => true
                ], 400);
            }

            // Check if answer is correct
            $isCorrect = $question->checkAnswer($request->student_answer);

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

            // Update assignment stats
            $assignment->total_attempts += 1;
            $assignment->save();

            return response()->json([
                'success' => true,
                'is_correct' => $isCorrect,
                'correct_answer' => $question->type === 'missing_number' ?
                    $question->getMissingNumber() : $question->answer,
                'message' => $isCorrect ? '✅ Correct!' : '❌ Incorrect.'
            ]);
        } catch (\Exception $e) {
            Log::error('Submit answer error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Complete the assignment
    public function complete(Request $request, $assignmentId)
    {
        try {
            $assignment = QuestionSetAssignment::where('id', $assignmentId)
                ->where('student_id', Auth::id())
                ->firstOrFail();

            if ($assignment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment already completed.'
                ], 403);
            }

            // Get ALL attempts for this assignment
            $attempts = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->get();

            // Get all questions in the set
            $totalQuestions = $assignment->questionSet->questions->count();

            // Count unique questions that were answered correctly
            // Using distinct() on the query to get unique question IDs that are correct
            $correctQuestions = StudentAttempt::where('student_id', Auth::id())
                ->where('question_set_id', $assignment->question_set_id)
                ->where('is_correct', true)
                ->distinct('question_id')
                ->count('question_id');

            // Calculate score
            $score = $totalQuestions > 0 ? round(($correctQuestions / $totalQuestions) * 100) : 0;

            $assignment->status = 'completed';
            $assignment->completed_at = now();
            $assignment->score = $score;
            $assignment->save();

            return response()->json([
                'success' => true,
                'message' => 'Assignment completed!',
                'score' => $score,
                'correct' => $correctQuestions,
                'total' => $totalQuestions
            ]);
        } catch (\Exception $e) {
            Log::error('Complete assignment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show results
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

        return view('student.assignments.results', compact('assignment', 'attempts'));
    }

    // Enroll in a public question set
    public function enroll(Request $request)
    {
        try {
            $request->validate([
                'question_set_id' => 'required|exists:question_sets,id'
            ]);

            $questionSet = QuestionSet::findOrFail($request->question_set_id);

            // Check if already enrolled
            $existing = QuestionSetAssignment::where('student_id', Auth::id())
                ->where('question_set_id', $request->question_set_id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already enrolled in this question set.'
                ], 403);
            }

            // Create assignment
            $assignment = QuestionSetAssignment::create([
                'question_set_id' => $request->question_set_id,
                'student_id' => Auth::id(),
                'status' => 'assigned',
                'assigned_at' => now(),
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
}