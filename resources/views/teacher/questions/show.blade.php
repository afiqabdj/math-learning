@extends('layouts.teachers')

@section('content')
    <div class="relative z-10 min-h-screen flex">

        <!-- Left Sidebar Navigation - White -->
        <div class="w-64 bg-white shadow-2xl flex-shrink-0 hidden md:block">
            <div class="p-6">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-2">👩‍🏫</div>
                    <h2 class="text-[#5C3A1E] font-bold text-xl">Teacher Portal</h2>
                    <p class="text-gray-500 text-sm">Math Quest Academy</p>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('teacher.dashboard') }}"
                        class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">📊</span>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="{{ route('teacher.questions.index') }}"
                        class="flex items-center space-x-3 bg-[#5C3A1E] text-white px-4 py-3 rounded-xl transition-all shadow-md">
                        <span class="text-2xl">📝</span>
                        <span class="font-semibold">Assignments</span>
                    </a>
                    <a href="{{ route('teacher.students') }}"
                        class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">👨‍🎓</span>
                        <span class="font-semibold">Students</span>
                    </a>
                    <a href="{{ route('teacher.teachers') }}" 
                        class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">👨‍🏫</span>
                        <span class="font-semibold">Teachers</span>
                    </a>
                </nav>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-[#5C3A1E]/20 flex items-center justify-center text-2xl">
                            📕
                        </div>
                        <div>
                            <p class="text-[#5C3A1E] font-semibold text-sm">{{ Auth::user()->name ?? 'Teacher' }}</p>
                            <p class="text-gray-400 text-xs">Online</p>
                        </div>
                    </div>
                    <a href="/logout"
                        class="mt-4 flex items-center space-x-2 text-gray-400 hover:text-[#5C3A1E] text-sm transition-all">
                        <span>🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white shadow-2xl border-t border-gray-200 z-50">
            <div class="flex justify-around py-3">
                <a href="{{ route('teacher.dashboard') }}" class="text-gray-400 text-2xl">📊</a>
                <a href="{{ route('teacher.questions.index') }}" class="text-[#5C3A1E] text-2xl">📝</a>
                <a href="{{ route('teacher.students') }}" class="text-gray-400 text-2xl">👨‍🎓</a>
                <a href="{{ route('teacher.teachers') }}" class="text-gray-400 text-2xl">👨‍🏫</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-h-screen pb-20 md:pb-8">
            <div class="p-4 md:p-8">

                <!-- Header -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                                <span>📋</span>
                                <span>{{ $questionSet->title }}</span>
                            </h1>
                            <p class="text-white/70 text-sm mt-1">{{ $questionSet->description ?? 'No description' }}</p>
                        </div>
                        <div class="flex items-center gap-3 mt-4 md:mt-0">
                            <a href="{{ route('teacher.questions.index') }}" 
                               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                                <span>⬅️</span> Back
                            </a>
                            <button onclick="window.print()" 
                                    class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                                🖨️ Print
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 shadow-xl text-center">
                        <p class="text-gray-500 text-xs uppercase tracking-wider">Questions</p>
                        <p class="text-2xl font-bold text-[#5C3A1E]">{{ $questionSet->questions->count() }}</p>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 shadow-xl text-center">
                        <p class="text-gray-500 text-xs uppercase tracking-wider">Operation</p>
                        <p class="text-2xl font-bold text-[#5C3A1E] capitalize">{{ $questionSet->operation_type }}</p>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 shadow-xl text-center">
                        <p class="text-gray-500 text-xs uppercase tracking-wider">Status</p>
                        <p class="text-2xl font-bold {{ $questionSet->is_public ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $questionSet->is_public ? '🌍 Public' : '🔒 Private' }}
                        </p>
                    </div>
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 shadow-xl text-center">
                        <p class="text-gray-500 text-xs uppercase tracking-wider">Time Limit</p>
                        <p class="text-2xl font-bold text-[#5C3A1E]">
                            {{ $questionSet->time_limit ? floor($questionSet->time_limit / 60) . 'min' : 'No limit' }}
                        </p>
                    </div>
                </div>

                <!-- Questions List -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                    <h3 class="text-[#5C3A1E] font-bold text-lg mb-4">📝 All Questions</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-[#D4A06A]">
                                    <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">#</th>
                                    <th class="py-3 px-4 text-[#5C3A1E] font-bold text-left">Question</th>
                                    <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Type</th>
                                    <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Answer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questionSet->questions as $question)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-center font-bold text-[#5C3A1E]">{{ $question->question_number }}</td>
                                        <td class="py-3 px-4 text-gray-700 text-lg">{{ $question->getDisplayForStudent() }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="text-xs px-2 py-1 rounded-full 
                                                {{ $question->type === 'two_numbers' ? 'bg-blue-100 text-blue-700' : 
                                                   ($question->type === 'three_numbers' ? 'bg-purple-100 text-purple-700' : 
                                                    'bg-orange-100 text-orange-700') }}">
                                                {{ str_replace('_', ' ', $question->type) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center font-bold text-green-600 text-lg">
                                            @if($question->type === 'missing_number')
                                                {{ $question->getMissingNumber() ?? $question->answer }}
                                            @else
                                                {{ $question->answer }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection