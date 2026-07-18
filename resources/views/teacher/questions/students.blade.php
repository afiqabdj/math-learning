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
                            <div class="flex items-center gap-3">
                                <a href="{{ route('teacher.questions.index') }}"
                                    class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                                    <span>⬅️</span> Back
                                </a>
                                <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                                    <span>👥</span>
                                    <span>Student Progress</span>
                                </h1>
                            </div>
                            <div
                                class="mt-3 ml-14 bg-white/10 backdrop-blur-sm rounded-xl px-6 py-3 inline-block border border-white/20">
                                <p class="text-2xl md:text-3xl font-bold text-white">
                                    Assignment: {{ $questionSet->title }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 mt-4 md:mt-0">
                            <a href="{{ route('teacher.questions.show', $questionSet->id) }}"
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                                <span>📋</span> View Questions
                            </a>
                            <button onclick="location.reload()"
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                                <span>🔄</span> Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Total Enrolled</p>
                                <p class="text-3xl font-bold text-[#5C3A1E] mt-1">{{ count($students) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-3xl">📚</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Completed</p>
                                <p class="text-3xl font-bold text-green-600 mt-1">{{ $completedCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-3xl">✅</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">In Progress</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $inProgressCount }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-3xl">⏳</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Average Score</p>
                                <p class="text-3xl font-bold text-purple-600 mt-1">{{ $averageScore }}%</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-3xl">📊</div>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#5C3A1E] font-bold text-lg">📋 Enrolled Students</h3>
                        <div class="flex items-center gap-2">
                            <input type="text" id="searchInput" placeholder="🔍 Search students..."
                                class="bg-gray-50 text-[#5C3A1E] rounded-lg px-4 py-2 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C3A1E] text-sm">
                            <span class="text-gray-500 text-sm">{{ count($students) }} total</span>
                        </div>
                    </div>

                    @if (count($students) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b-2 border-[#D4A06A]">
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-left">#</th>
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-left">Student Name</th>
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-left">Username</th>
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Status</th>
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Score</th>
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Completed At</th>
                                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                    @foreach ($students as $index => $student)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors student-row">
                                            <td class="py-3 px-4 text-gray-500">{{ $index + 1 }}</td>
                                            <td class="py-3 px-4 font-medium text-[#5C3A1E]">{{ $student['name'] }}</td>
                                            <td class="py-3 px-4 text-gray-600">{{ $student['username'] }}</td>
                                            <td class="py-3 px-4 text-center">
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-bold 
                                                {{ $student['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ $student['status'] === 'completed' ? '✅ Completed' : '⏳ In Progress' }}
                                                </span>
                                            </td>
                                            <td
                                                class="py-3 px-4 text-center font-bold {{ isset($student['score']) && $student['score'] ? 'text-green-600' : 'text-gray-400' }}">
                                                {{ isset($student['score']) && $student['score'] ? $student['score'] . '%' : '-' }}
                                            </td>
                                            <td class="py-3 px-4 text-center text-gray-500 text-sm">
                                                {{ isset($student['completed_at']) && $student['completed_at'] ? \Carbon\Carbon::parse($student['completed_at'])->format('M d, Y h:i A') : '-' }}
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                @if ($student['status'] === 'completed')
                                                    <a href="{{ route('teacher.questions.students.results', ['assignmentId' => $student['assignment_id'], 'studentId' => $student['id']]) }}"
                                                        class="text-blue-500 hover:text-blue-700 text-sm font-semibold transition-all">
                                                        📊 View Results
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-sm">Not completed yet</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📭</div>
                            <p class="text-gray-400 text-lg">No students have enrolled in this set yet.</p>
                            <p class="text-gray-400 text-sm mt-1">Make it public or share the link with students.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.student-row');

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });
    </script>
@endsection