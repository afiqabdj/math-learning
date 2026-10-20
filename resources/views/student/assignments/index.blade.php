@extends('layouts.student')

@section('content')
    <div class="relative z-10 min-h-screen py-8 px-4">

        <!-- Header - Bigger and more fun -->
        <div class="mb-10 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1
                        class="text-5xl md:text-6xl font-bold text-white flex items-center gap-4 justify-center md:justify-start">
                        <span class="text-6xl">📝</span>
                        <span>My Assignments</span>
                    </h1>
                </div>
            </div>
        </div>

        <!-- Stats Cards - Bigger and more colorful -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <div
                class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-3xl p-6 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-lg font-semibold uppercase tracking-wider">📚 Total Assignments</p>
                        <p class="text-6xl font-bold text-white mt-2">{{ $assignments->count() }}</p>
                    </div>
                    <div
                        class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center text-5xl backdrop-blur-sm">
                        📚</div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-400 to-green-600 rounded-3xl p-6 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/90 text-lg font-semibold uppercase tracking-wider">✅ Completed</p>
                        <p class="text-6xl font-bold text-white mt-2">
                            {{ $assignments->where('status', 'completed')->count() }}</p>
                    </div>
                    <div
                        class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center text-5xl backdrop-blur-sm">
                        ✅</div>
                </div>
            </div>
        </div>

        <!-- Available Public Sets -->
        @if ($availableSets->count() > 0)
            <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-8 shadow-2xl mb-10 border-4 border-yellow-300">
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-5xl">🌍</span>
                    <h3 class="text-3xl font-bold text-[#5C3A1E]">Available Public Sets</h3>
                    <span
                        class="bg-yellow-400 text-[#5C3A1E] px-4 py-1 rounded-full text-lg font-bold">{{ $availableSets->count() }}
                        new</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($availableSets as $set)
                        <div
                            class="bg-gradient-to-br from-white to-yellow-50 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-yellow-200 p-6 transform hover:scale-105">
                            <h4 class="text-2xl font-bold text-[#5C3A1E]">{{ $set->title }}</h4>
                            <p class="text-base text-gray-600 mt-2">{{ $set->description ?? 'No description' }}</p>
                            <div class="flex flex-wrap gap-3 mt-4">
                                <span class="text-sm bg-purple-200 text-purple-800 px-4 py-2 rounded-full font-semibold">
                                    📝 {{ $set->questions->count() }} questions
                                </span>
                                <span
                                    class="text-sm px-4 py-2 rounded-full font-semibold 
    {{ $set->operation_type === 'subtraction' ? 'bg-purple-200 text-purple-800' : 'bg-blue-200 text-blue-800' }}">
                                    {{ $set->operation_type === 'subtraction' ? '➖ Subtraction' : '➕ Addition' }}
                                </span>
                                @if ($set->time_limit)
                                    <span
                                        class="text-sm bg-orange-200 text-orange-800 px-4 py-2 rounded-full font-semibold">
                                        ⏱️ {{ floor($set->time_limit / 60) }}min
                                    </span>
                                @endif
                                <span class="text-sm bg-pink-200 text-pink-800 px-4 py-2 rounded-full font-semibold">
                                    👩‍🏫 By: {{ $set->teacher->name ?? 'Unknown' }}
                                </span>
                            </div>
                            <button onclick="enrollSet({{ $set->id }})"
                                class="mt-4 w-full bg-gradient-to-r from-[#5C3A1E] to-[#8B5A3A] hover:from-[#7A4A2A] hover:to-[#A06A4A] text-white text-xl font-bold py-4 rounded-2xl transition-all shadow-lg transform hover:scale-105">
                                📚 Enroll Now!
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- My Assignments -->
        <div class="bg-white/95 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border-4 border-blue-200">
            <div class="flex items-center gap-4 mb-6">
                <span class="text-5xl">📋</span>
                <h3 class="text-3xl font-bold text-[#5C3A1E]">My Assignments</h3>
                <span class="bg-blue-400 text-white px-4 py-1 rounded-full text-lg font-bold">{{ $assignments->count() }}
                    total</span>
            </div>

            @if ($assignments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($assignments as $assignment)
                        <div
                            class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 border-2 {{ $assignment->status === 'completed' ? 'border-green-300' : 'border-blue-300' }} overflow-hidden transform hover:scale-105">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="text-2xl font-bold text-[#5C3A1E]">{{ $assignment->questionSet->title }}
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                                            👩‍🏫 By: {{ $assignment->questionSet->teacher->name ?? 'Unknown' }}
                                        </p>
                                    </div>
                                    <span
                                        class="text-sm px-4 py-2 rounded-full font-bold 
                                    {{ $assignment->status === 'completed'
                                        ? 'bg-green-200 text-green-800'
                                        : ($assignment->status === 'in_progress'
                                            ? 'bg-yellow-200 text-yellow-800'
                                            : 'bg-blue-200 text-blue-800') }}">
                                        @if ($assignment->status === 'completed')
                                            ✅ Done!
                                        @elseif($assignment->status === 'in_progress')
                                            ⏳ In Progress
                                        @else
                                            📌 Ready
                                        @endif
                                    </span>
                                </div>

                                <p class="text-base text-gray-600 mb-4">
                                    {{ $assignment->questionSet->description ?? 'No description' }}</p>

                                <div class="flex flex-wrap gap-3 mb-4">
                                    <span
                                        class="text-sm bg-purple-200 text-purple-800 px-4 py-2 rounded-full font-semibold">
                                        📝 {{ $assignment->questionSet->questions->count() }} questions
                                    </span>
                                    <span
                                        class="text-sm px-4 py-2 rounded-full font-semibold 
    {{ ($assignment->questionSet->operation_type ?? 'addition') === 'subtraction' ? 'bg-purple-200 text-purple-800' : 'bg-blue-200 text-blue-800' }}">
                                        {{ ($assignment->questionSet->operation_type ?? 'addition') === 'subtraction' ? '➖ Subtraction' : '➕ Addition' }}
                                    </span>
                                    @if ($assignment->questionSet->time_limit)
                                        <span
                                            class="text-sm bg-orange-200 text-orange-800 px-4 py-2 rounded-full font-semibold">
                                            ⏱️ {{ floor($assignment->questionSet->time_limit / 60) }}min
                                        </span>
                                    @endif
                                    @if ($assignment->score)
                                        <span
                                            class="text-sm bg-green-200 text-green-800 px-4 py-2 rounded-full font-semibold">
                                            🎯 {{ $assignment->score }}%
                                        </span>
                                    @endif
                                </div>

                                @if ($assignment->status === 'completed')
                                    <a href="{{ route('student.assignments.results', $assignment->id) }}"
                                        class="w-full inline-block text-center bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-xl font-bold py-4 rounded-2xl transition-all shadow-lg transform hover:scale-105">
                                        📊 View Results
                                    </a>
                                @else
                                    <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                        class="w-full inline-block text-center bg-gradient-to-r from-[#5C3A1E] to-[#8B5A3A] hover:from-[#7A4A2A] hover:to-[#A06A4A] text-white text-xl font-bold py-4 rounded-2xl transition-all shadow-lg transform hover:scale-105">
                                        🚀 Continue
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-8xl mb-6">📭</div>
                    <p class="text-3xl font-bold text-gray-500">No assignments yet!</p>
                    <p class="text-xl text-gray-400 mt-3">Enroll in a public set above or wait for your teacher to assign
                        one.</p>
                    <div class="mt-6 text-4xl">🌟</div>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function enrollSet(setId) {
            Swal.fire({
                title: '📚 Enroll in this set?',
                text: 'You can start working on it right away!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5C3A1E',
                cancelButtonColor: '#d33',
                confirmButtonText: '✅ Yes, Enroll!',
                cancelButtonText: '❌ Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '⏳ Enrolling...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route('student.assignments.enroll') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                question_set_id: setId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '✅ Enrolled! 🎉',
                                    text: data.message,
                                    confirmButtonColor: '#5C3A1E',
                                    confirmButtonText: '👍 Awesome!'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: '❌ Oops!',
                                    text: data.error || 'Failed to enroll',
                                    confirmButtonColor: '#5C3A1E',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: '❌ Oops!',
                                text: 'Something went wrong',
                                confirmButtonColor: '#5C3A1E',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        }

        window.enrollSet = enrollSet;
    </script>

    <style>
        /* Fun animations for kids */
        .transform {
            transition: transform 0.3s ease;
        }

        .hover\:scale-105:hover {
            transform: scale(1.05);
        }

        /* Bigger touch targets for kids */
        button,
        a {
            cursor: pointer;
            min-height: 60px;
            touch-action: manipulation;
        }

        /* Fun rounded corners */
        .rounded-3xl {
            border-radius: 2rem;
        }

        .rounded-2xl {
            border-radius: 1.5rem;
        }

        /* Bigger text for readability */
        @media (max-width: 640px) {
            .text-5xl {
                font-size: 2.5rem;
            }

            .text-6xl {
                font-size: 3rem;
            }

            .text-3xl {
                font-size: 1.8rem;
            }

            .text-2xl {
                font-size: 1.5rem;
            }

            h1 {
                font-size: 2.5rem !important;
            }
        }
    </style>
@endsection
