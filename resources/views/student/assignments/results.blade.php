@extends('layouts.student')

@section('content')
<div class="relative z-10 min-h-screen py-8 px-4">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="text-6xl mb-4">
            @if($assignment->score >= 80)
                🌟
            @elseif($assignment->score >= 50)
                👍
            @else
                💪
            @endif
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-black">Assignment Complete! 🎉</h1>
        <p class="text-white/70 text-sm mt-1">{{ $assignment->questionSet->title }}</p>
    </div>

    <!-- Score Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl text-center">
            <p class="text-gray-500 text-xs uppercase tracking-wider">Your Score</p>
            <p class="text-4xl font-bold text-[#5C3A1E] mt-1">{{ $assignment->score }}%</p>
        </div>
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl text-center">
            <p class="text-gray-500 text-xs uppercase tracking-wider">Correct</p>
            <p class="text-3xl font-bold text-green-600 mt-1">
                {{ $assignment->score ? round(($assignment->score / 100) * $assignment->questionSet->questions->count()) : 0 }}
            </p>
        </div>
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl text-center">
            <p class="text-gray-500 text-xs uppercase tracking-wider">Total Questions</p>
            <p class="text-3xl font-bold text-[#5C3A1E] mt-1">{{ $assignment->questionSet->questions->count() }}</p>
        </div>
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl text-center">
            <p class="text-gray-500 text-xs uppercase tracking-wider">Status</p>
            <p class="text-2xl font-bold text-green-600 mt-1">✅ Completed</p>
        </div>
    </div>

    <!-- Message based on score -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 mb-6 text-center shadow-lg">
        @if($assignment->score >= 80)
            <p class="text-green-600 text-lg font-semibold">🌟 Amazing work! You're a math superstar!</p>
        @elseif($assignment->score >= 50)
            <p class="text-yellow-600 text-lg font-semibold">👍 Good effort! Keep practicing to improve!</p>
        @else
            <p class="text-blue-600 text-lg font-semibold">💪 Don't give up! Practice makes perfect!</p>
        @endif
    </div>

    <!-- Detailed Results -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
        <h3 class="text-[#5C3A1E] font-bold text-lg mb-4">📋 Detailed Results</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-[#D4A06A]">
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">#</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-left">Question</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Your Answer</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Correct Answer</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignment->questionSet->questions as $question)
                        @php
                            // Check if attempts exist for this question
                            $attempt = isset($attempts[$question->id]) ? $attempts[$question->id]->first() : null;
                            $isCorrect = $attempt && $attempt->is_correct;
                            $studentAnswer = $attempt ? $attempt->student_answer : '-';
                            $correctAnswer = $question->type === 'missing_number' ? 
                                $question->getMissingNumber() : $question->answer;
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-[#5C3A1E]">{{ $question->question_number }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $question->getDisplayText() }}</td>
                            <td class="py-3 px-4 text-center font-semibold {{ $isCorrect ? 'text-green-600' : ($attempt ? 'text-red-600' : 'text-gray-400') }}">
                                {{ $studentAnswer }}
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-green-600">{{ $correctAnswer }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($isCorrect)
                                    <span class="text-green-600 text-xl">✅</span>
                                @elseif($attempt)
                                    <span class="text-red-600 text-xl">❌</span>
                                @else
                                    <span class="text-gray-400 text-xl">⬜</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Back button -->
        <div class="mt-6 text-center">
            <a href="{{ route('student.assignments.index') }}" 
               class="inline-block bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-8 py-3 rounded-lg font-bold transition-all shadow-lg">
                📚 Back to Assignments
            </a>
        </div>
    </div>
</div>
@endsection