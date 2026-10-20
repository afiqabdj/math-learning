<?php

namespace App\Http\Controllers;

use App\Models\QuestionSet;
use App\Models\QuestionSetAssignment;
use App\Models\StudentAttempt;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherQuestionController extends Controller
{
    // Display all question sets
    public function index()
    {
        $questionSets = QuestionSet::where('teacher_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.questions.index', compact('questionSets'));
    }

    // Show create form
    public function create()
    {
        return view('teacher.questions.create');
    }

    // Generate preview questions (without saving)
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'number_of_questions' => 'required|integer|min:1|max:50',
                'min_value' => 'required|integer|min:0',
                'max_value' => 'required|integer|gt:min_value',
                'operation_type' => 'required|in:addition,subtraction',
            ]);

            $questions = $this->generateQuestionsArray(
                $request->number_of_questions,
                $request->min_value,
                $request->max_value,
                $request->operation_type
            );

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            Log::error('Preview error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Store a new question set
    public function store(Request $request)
    {
        try {
            Log::info('Store request received', $request->all());

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'number_of_questions' => 'required|integer|min:1|max:50',
                'time_limit' => 'nullable|integer|min:10',
                'is_public' => 'boolean',
                'min_value' => 'required|integer|min:0',
                'max_value' => 'required|integer|gt:min_value',
                'operation_type' => 'required|in:addition,subtraction',
                'questions' => 'required|array|min:1',
            ]);

            $questionSet = QuestionSet::create([
                'teacher_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'operation_type' => $request->operation_type, // Now dynamic
                'number_of_questions' => $request->number_of_questions,
                'time_limit' => $request->time_limit,
                'is_public' => $request->is_public ?? false,
                'is_active' => true,
                'min_value' => $request->min_value,
                'max_value' => $request->max_value,
            ]);

            // Save the questions
            foreach ($request->questions as $questionData) {
                Question::create([
                    'question_set_id' => $questionSet->id,
                    'question_number' => $questionData['question_number'],
                    'type' => $questionData['type'],
                    'num1' => $questionData['num1'],
                    'num2' => $questionData['num2'],
                    'num3' => $questionData['num3'] ?? null,
                    'answer' => $questionData['answer'],
                    'missing_position' => $questionData['missing_position'] ?? null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Question set created successfully!',
                'question_set_id' => $questionSet->id
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Store error: ' . $e->getMessage());
            Log::error('Store trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Generate questions array with operation type support
    private function generateQuestionsArray($count, $minValue, $maxValue, $operationType = 'addition')
    {
        $questions = [];

        for ($i = 1; $i <= $count; $i++) {
            // Randomly choose question type
            $type = $this->getRandomQuestionType($i, $count);

            if ($operationType === 'subtraction') {
                // Subtraction questions
                switch ($type) {
                    case 'two_numbers':
                        // Two numbers subtraction: num1 - num2 = answer (num1 >= num2)
                        $num1 = rand($minValue, $maxValue);
                        $num2 = rand($minValue, $num1);
                        $answer = $num1 - $num2;
                        $display = "{$num1} - {$num2} = ⬜";

                        $question = [
                            'question_number' => $i,
                            'type' => 'two_numbers',
                            'num1' => $num1,
                            'num2' => $num2,
                            'num3' => null,
                            'answer' => $answer,
                            'missing_position' => null,
                            'display' => $display,
                            'answer_display' => $answer,
                            'operation' => 'subtraction'
                        ];
                        break;

                    case 'three_numbers':
                        // Three numbers subtraction: num1 - num2 - num3 = answer (num1 >= num2 + num3)
                        $num1 = rand($minValue + 2, $maxValue);
                        $num2 = rand($minValue, floor($num1 / 2));
                        $num3 = rand($minValue, $num1 - $num2);
                        $answer = $num1 - $num2 - $num3;
                        $display = "{$num1} - {$num2} - {$num3} = ⬜";

                        $question = [
                            'question_number' => $i,
                            'type' => 'three_numbers',
                            'num1' => $num1,
                            'num2' => $num2,
                            'num3' => $num3,
                            'answer' => $answer,
                            'missing_position' => null,
                            'display' => $display,
                            'answer_display' => $answer,
                            'operation' => 'subtraction'
                        ];
                        break;

                    case 'missing_number':
                        // Missing number subtraction: num1 - ? - num3 = answer
                        // or num1 - num2 - ? = answer
                        $num1 = rand($minValue + 2, $maxValue);
                        $num2 = rand($minValue, floor($num1 / 2));
                        $num3 = rand($minValue, $num1 - $num2);
                        $answer = $num1 - $num2 - $num3;
                        
                        $missing_position = rand(1, 3);
                        $parts = [$num1, $num2, $num3];
                        $displayParts = $parts;
                        $displayParts[$missing_position - 1] = '⬜';
                        $display = implode(' - ', $displayParts) . " = {$answer}";

                        $question = [
                            'question_number' => $i,
                            'type' => 'missing_number',
                            'num1' => $num1,
                            'num2' => $num2,
                            'num3' => $num3,
                            'answer' => $answer,
                            'missing_position' => $missing_position,
                            'display' => $display,
                            'answer_display' => $parts[$missing_position - 1],
                            'operation' => 'subtraction'
                        ];
                        break;
                }
            } else {
                // Addition questions (original logic)
                switch ($type) {
                    case 'two_numbers':
                        // Two numbers addition: num1 + num2 = answer
                        $num1 = rand($minValue, $maxValue);
                        $num2 = rand($minValue, $maxValue - $num1);
                        $answer = $num1 + $num2;
                        $display = "{$num1} + {$num2} = ⬜";

                        $question = [
                            'question_number' => $i,
                            'type' => 'two_numbers',
                            'num1' => $num1,
                            'num2' => $num2,
                            'num3' => null,
                            'answer' => $answer,
                            'missing_position' => null,
                            'display' => $display,
                            'answer_display' => $answer,
                            'operation' => 'addition'
                        ];
                        break;

                    case 'three_numbers':
                        // Three numbers addition: num1 + num2 + num3 = answer
                        $num1 = rand($minValue, floor($maxValue / 2));
                        $num2 = rand($minValue, floor(($maxValue - $num1) / 2));
                        $num3 = rand($minValue, $maxValue - $num1 - $num2);
                        $answer = $num1 + $num2 + $num3;
                        $display = "{$num1} + {$num2} + {$num3} = ⬜";

                        $question = [
                            'question_number' => $i,
                            'type' => 'three_numbers',
                            'num1' => $num1,
                            'num2' => $num2,
                            'num3' => $num3,
                            'answer' => $answer,
                            'missing_position' => null,
                            'display' => $display,
                            'answer_display' => $answer,
                            'operation' => 'addition'
                        ];
                        break;

                    case 'missing_number':
                        // Missing number addition: num1 + ? + num3 = answer
                        $num1 = rand($minValue, $maxValue);
                        $num3 = rand($minValue, $maxValue);
                        $answer = rand($num1 + $num3 + 1, $maxValue + $num1 + $num3);
                        $num2 = $answer - $num1 - $num3;
                        $missing_position = rand(1, 3);
                        $parts = [$num1, $num2, $num3];
                        $displayParts = $parts;
                        $displayParts[$missing_position - 1] = '⬜';
                        $display = implode(' + ', $displayParts) . " = {$answer}";

                        $question = [
                            'question_number' => $i,
                            'type' => 'missing_number',
                            'num1' => $num1,
                            'num2' => $num2,
                            'num3' => $num3,
                            'answer' => $answer,
                            'missing_position' => $missing_position,
                            'display' => $display,
                            'answer_display' => $parts[$missing_position - 1],
                            'operation' => 'addition'
                        ];
                        break;
                }
            }

            $questions[] = $question;
        }

        return $questions;
    }

    // Get random question type with balanced distribution
    private function getRandomQuestionType($index, $total)
    {
        $percentage = ($index / $total) * 100;

        if ($percentage <= 30) {
            return 'two_numbers';
        } elseif ($percentage <= 70) {
            return 'three_numbers';
        } else {
            return 'missing_number';
        }
    }

    // Show a specific question set
    public function show($id)
    {
        $questionSet = QuestionSet::with('questions')
            ->where('teacher_id', Auth::id())
            ->findOrFail($id);

        return view('teacher.questions.show', compact('questionSet'));
    }

    // Delete a question set
    public function destroy($id)
    {
        try {
            $questionSet = QuestionSet::where('teacher_id', Auth::id())->findOrFail($id);
            $questionSet->delete();

            return response()->json([
                'success' => true,
                'message' => 'Question set deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Toggle public/private status
    public function togglePublic($id)
    {
        try {
            $questionSet = QuestionSet::where('teacher_id', Auth::id())->findOrFail($id);
            $questionSet->is_public = !$questionSet->is_public;
            $questionSet->save();

            return response()->json([
                'success' => true,
                'message' => 'Question set status updated!',
                'is_public' => $questionSet->is_public
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Edit question set
    public function edit($id)
    {
        $questionSet = QuestionSet::with('questions')
            ->where('teacher_id', Auth::id())
            ->findOrFail($id);

        return view('teacher.questions.edit', compact('questionSet'));
    }

    // Update question set
    public function update(Request $request, $id)
    {
        try {
            $questionSet = QuestionSet::where('teacher_id', Auth::id())->findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'time_limit' => 'nullable|integer|min:10',
                'is_public' => 'boolean',
            ]);

            $questionSet->update([
                'title' => $request->title,
                'description' => $request->description,
                'time_limit' => $request->time_limit,
                'is_public' => $request->is_public ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Question set updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function students($id)
    {
        try {
            $questionSet = QuestionSet::where('teacher_id', Auth::id())
                ->with('questions')
                ->findOrFail($id);

            // Get all assignments for this question set
            $assignments = QuestionSetAssignment::where('question_set_id', $id)
                ->with('student')
                ->get();

            $students = [];
            $completedCount = 0;
            $inProgressCount = 0;
            $totalScore = 0;
            $scoreCount = 0;

            foreach ($assignments as $assignment) {
                $studentData = [
                    'id' => $assignment->student->id,
                    'name' => $assignment->student->name,
                    'username' => $assignment->student->username,
                    'status' => $assignment->status,
                    'score' => $assignment->score,
                    'completed_at' => $assignment->completed_at,
                    'assignment_id' => $assignment->id,
                ];

                $students[] = $studentData;

                if ($assignment->status === 'completed') {
                    $completedCount++;
                    if ($assignment->score !== null) {
                        $totalScore += $assignment->score;
                        $scoreCount++;
                    }
                } else {
                    $inProgressCount++;
                }
            }

            $averageScore = $scoreCount > 0 ? round($totalScore / $scoreCount) : 0;

            return view('teacher.questions.students', compact(
                'questionSet',
                'students',
                'completedCount',
                'inProgressCount',
                'averageScore'
            ));
        } catch (\Exception $e) {
            Log::error('Get students page error: ' . $e->getMessage());
            return redirect()->route('teacher.questions.index')
                ->with('error', 'Failed to load student data.');
        }
    }

    // View student's assignment results (for teachers)
    public function viewStudentResults($assignmentId, $studentId)
    {
        try {
            // Check if the teacher owns this question set
            $assignment = QuestionSetAssignment::with(['questionSet', 'student'])
                ->where('id', $assignmentId)
                ->where('student_id', $studentId)
                ->firstOrFail();

            // Verify the teacher owns this question set
            if ($assignment->questionSet->teacher_id !== Auth::id()) {
                abort(403, 'Unauthorized access.');
            }

            $attempts = StudentAttempt::where('student_id', $studentId)
                ->where('question_set_id', $assignment->question_set_id)
                ->with('question')
                ->get()
                ->groupBy('question_id');

            return view('teacher.questions.student-results', compact('assignment', 'attempts'));
        } catch (\Exception $e) {
            Log::error('View student results error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load results.');
        }
    }
}