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
                                <span>📝</span>
                                <span>Question Bank</span>
                            </h1>
                            <p class="text-white/70 text-sm mt-1">Create and manage custom math questions</p>
                        </div>
                        <div class="flex items-center gap-3 mt-4 md:mt-0">
                            <a href="{{ route('teacher.questions.create') }}"
                                class="bg-[#F4A623] hover:bg-[#D4890B] text-white px-6 py-2 rounded-lg font-bold transition-all flex items-center gap-2 shadow-lg">
                                <span>➕</span> Create New Set
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Total Sets</p>
                                <p class="text-3xl font-bold text-[#5C3A1E] mt-1">{{ $questionSets->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-3xl">📚</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Public Sets</p>
                                <p class="text-3xl font-bold text-[#F4A623] mt-1">
                                    {{ $questionSets->where('is_public', true)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-3xl">🌍</div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Total Questions</p>
                                <p class="text-3xl font-bold text-[#5C3A1E] mt-1">{{ $questionSets->sum('number_of_questions') }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-3xl">❓</div>
                        </div>
                    </div>
                </div>

                <!-- Question Sets List -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#5C3A1E] font-bold text-lg">📋 Your Question Sets</h3>
                        <div class="flex items-center gap-2">
                            <input type="text" id="searchInput" placeholder="🔍 Search sets..."
                                class="bg-gray-50 text-[#5C3A1E] rounded-lg px-4 py-2 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C3A1E] text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($questionSets as $set)
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden question-set-card">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="text-lg font-bold text-[#5C3A1E]">{{ $set->title }}</h4>
                                        <span
                                            class="text-xs px-2 py-1 rounded-full {{ $set->is_public ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $set->is_public ? '🌍 Public' : '🔒 Private' }}
                                        </span>
                                    </div>

                                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $set->description ?? 'No description' }}
                                    </p>

                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">
                                            {{ $set->questions->count() }} questions
                                        </span>
                                        @if ($set->time_limit)
                                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full">
                                                ⏱️ {{ floor($set->time_limit / 60) }}min
                                            </span>
                                        @endif
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                            {{ ucfirst($set->operation_type) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('teacher.questions.show', $set->id) }}"
                                            class="flex-1 text-center bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-3 py-2 rounded-lg text-sm font-semibold transition-all">
                                            View
                                        </a>
                                        <a href="{{ route('teacher.questions.students', $set->id) }}"
                                            class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-sm font-semibold transition-all" 
                                            title="View Students">
                                            👥
                                        </a>
                                        <button onclick="togglePublic({{ $set->id }})"
                                            class="px-3 py-2 rounded-lg text-sm font-semibold transition-all {{ $set->is_public ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                            {{ $set->is_public ? '🌍' : '🔒' }}
                                        </button>
                                        <button onclick="deleteSet({{ $set->id }})"
                                            class="px-3 py-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg text-sm font-semibold transition-all">
                                            🗑️
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-12">
                                <div class="text-6xl mb-4">📭</div>
                                <p class="text-gray-400 text-lg">No question sets created yet</p>
                                <a href="{{ route('teacher.questions.create') }}"
                                    class="text-[#5C3A1E] font-semibold hover:underline">
                                    Create your first set →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const cards = document.querySelectorAll('.question-set-card');

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    cards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        card.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });

        // Toggle public status
        function togglePublic(setId) {
            Swal.fire({
                title: 'Toggle Public Status?',
                text: 'This will make the question set available to all teachers.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5C3A1E',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, toggle it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/teacher/questions/${setId}/toggle-public`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: data.message,
                                    confirmButtonColor: '#5C3A1E'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong',
                                confirmButtonColor: '#5C3A1E'
                            });
                        });
                }
            });
        }

        // Delete question set
        function deleteSet(setId) {
            Swal.fire({
                title: 'Delete Question Set?',
                text: 'This action cannot be undone! All questions will be deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#5C3A1E',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/teacher/questions/${setId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: data.message,
                                    confirmButtonColor: '#5C3A1E'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong',
                                confirmButtonColor: '#5C3A1E'
                            });
                        });
                }
            });
        }

        // Make functions globally accessible
        window.togglePublic = togglePublic;
        window.deleteSet = deleteSet;
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection