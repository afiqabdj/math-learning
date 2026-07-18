@extends('layouts.student')

@section('content')
    <div class="relative z-10 min-h-screen py-8 px-4">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                        <span>📝</span>
                        <span>{{ $assignment->questionSet->title }}</span>
                    </h1>
                    <p class="text-white/70 text-sm mt-1">{{ $assignment->questionSet->description ?? 'No description' }}</p>
                </div>
                <div class="flex items-center gap-3 mt-4 md:mt-0">
                    <a href="{{ route('student.assignments.index') }}"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                        <span>⬅️</span> Back
                    </a>
                    <div id="timerDisplay"
                        class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg text-sm font-bold hidden">
                        ⏱️ <span id="timer">00:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-4 shadow-xl mb-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Question <span id="currentQuestionNum">1</span> of <span
                        id="totalQuestions">{{ $assignment->questionSet->questions->count() }}</span>
                </div>
                <div class="text-sm text-gray-600">
                    ✅ <span id="correctCount">0</span> correct
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                <div id="progressBar" class="bg-[#5C3A1E] h-2.5 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-xl max-w-3xl mx-auto">
            <div id="questionContainer">
                <!-- Question will be rendered here -->
            </div>

            <!-- Answer Input -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Your Answer:</label>
                <div class="flex gap-4">
                    <input type="number" id="answerInput"
                        class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-xl"
                        placeholder="Type your answer..." autofocus>
                    <button id="submitBtn"
                        class="bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-8 py-3 rounded-lg font-bold text-lg transition-all shadow-lg">
                        Submit
                    </button>
                </div>
                <div id="feedback" class="mt-3 text-center text-lg font-semibold hidden"></div>
            </div>

            <!-- Complete Button -->
            <div class="mt-8 text-center">
                <button id="completeBtn"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg transition-all shadow-lg hidden">
                    ✅ Complete Assignment
                </button>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let currentQuestionIndex = 0;
        let questions = @json($assignment->questionSet->questions);
        let totalQuestions = questions.length;
        let correctCount = 0;
        let answeredQuestions = new Set(); // Track questions answered correctly
        let wrongAttempts = new Set(); // Track questions answered wrong
        let attemptedQuestions = new Set(); // Track ALL questions attempted (correct or wrong)
        let timerInterval = null;
        let seconds = 0;
        let timeLimit = {{ $assignment->questionSet->time_limit ?? 0 }};
        let startTime = Date.now();

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Start timer if time limit is set
            if (timeLimit > 0) {
                document.getElementById('timerDisplay').classList.remove('hidden');
                startTimer();
            }

            renderQuestion(0);
            updateProgress();

            // Submit answer on Enter key
            document.getElementById('answerInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    submitAnswer();
                }
            });

            // Submit button
            document.getElementById('submitBtn').addEventListener('click', submitAnswer);

            // Complete button
            document.getElementById('completeBtn').addEventListener('click', function() {
                completeAssignment();
            });
        });

        function renderQuestion(index) {
            const question = questions[index];
            const container = document.getElementById('questionContainer');

            // Get the display text for student
            let displayText = question.display || question.display_text || '';

            // If no display text is available, construct it
            if (!displayText) {
                if (question.type === 'two_numbers') {
                    displayText = `${question.num1} + ${question.num2} = ?`;
                } else if (question.type === 'three_numbers') {
                    displayText = `${question.num1} + ${question.num2} + ${question.num3} = ?`;
                } else if (question.type === 'missing_number') {
                    const parts = [question.num1, question.num2, question.num3];
                    const displayParts = parts;
                    displayParts[question.missing_position - 1] = '?';
                    displayText = displayParts.join(' + ') + ` = ${question.answer}`;
                }
            }

            // Format the question display
            let html = `
                <div class="text-center">
                    <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Q${index + 1}</span>
                    <div class="text-4xl font-bold text-[#5C3A1E] mt-4">
                        ${displayText}
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        Type: ${question.type.replace('_', ' ')}
                    </div>
                </div>
            `;

            container.innerHTML = html;

            // Update current question number
            document.getElementById('currentQuestionNum').textContent = index + 1;

            // Focus on input
            document.getElementById('answerInput').focus();
            document.getElementById('answerInput').value = '';
            document.getElementById('feedback').classList.add('hidden');
            document.getElementById('feedback').textContent = '';

            // Check if this question was already answered
            if (attemptedQuestions.has(question.id)) {
                // Already attempted - disable input
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('answerInput').disabled = true;

                if (answeredQuestions.has(question.id)) {
                    // Answered correctly
                    document.getElementById('feedback').classList.remove('hidden');
                    document.getElementById('feedback').textContent = '✅ Already answered correctly!';
                    document.getElementById('feedback').className = 'mt-3 text-center text-lg font-semibold text-green-600';
                } else if (wrongAttempts.has(question.id)) {
                    // Answered wrong
                    document.getElementById('feedback').classList.remove('hidden');
                    document.getElementById('feedback').textContent = '❌ You already answered this incorrectly.';
                    document.getElementById('feedback').className = 'mt-3 text-center text-lg font-semibold text-red-600';
                }
            } else {
                // Not answered yet - allow answering
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('answerInput').disabled = false;
            }
        }

        function submitAnswer() {
            const question = questions[currentQuestionIndex];
            const answerInput = document.getElementById('answerInput');
            const answer = parseInt(answerInput.value);
            const feedback = document.getElementById('feedback');

            // Validate
            if (isNaN(answer)) {
                feedback.textContent = '⚠️ Please enter a number';
                feedback.className = 'mt-3 text-center text-lg font-semibold text-red-600';
                feedback.classList.remove('hidden');
                return;
            }

            // Check if already answered
            if (attemptedQuestions.has(question.id)) {
                feedback.textContent = '⚠️ You already answered this question!';
                feedback.className = 'mt-3 text-center text-lg font-semibold text-yellow-600';
                feedback.classList.remove('hidden');
                return;
            }

            // Calculate time taken for this question
            const timeTaken = Math.floor((Date.now() - startTime) / 1000);

            // Submit to server
            fetch('{{ route('student.assignments.submit') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        assignment_id: {{ $assignment->id }},
                        question_id: question.id,
                        student_answer: answer,
                        time_taken: timeTaken
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mark as attempted
                        attemptedQuestions.add(question.id);

                        if (data.is_correct) {
                            // Correct answer - increment correct count
                            correctCount++;
                            answeredQuestions.add(question.id);
                            feedback.textContent = '✅ Correct! Well done! 🎉';
                            feedback.className = 'mt-3 text-center text-lg font-semibold text-green-600';
                            document.getElementById('correctCount').textContent = correctCount;
                        } else {
                            // Wrong answer
                            wrongAttempts.add(question.id);
                            feedback.textContent = '❌ Incorrect. The correct answer was ' + data.correct_answer + '.';
                            feedback.className = 'mt-3 text-center text-lg font-semibold text-red-600';
                            // DO NOT increment correctCount for wrong answers
                        }

                        // Disable submit for this question
                        document.getElementById('submitBtn').disabled = true;
                        document.getElementById('answerInput').disabled = true;
                        feedback.classList.remove('hidden');

                        // Check if all questions are now attempted
                        const totalAttempted = attemptedQuestions.size;

                        // Move to next question after delay
                        setTimeout(() => {
                            if (currentQuestionIndex < totalQuestions - 1) {
                                currentQuestionIndex++;
                                renderQuestion(currentQuestionIndex);
                                updateProgress();
                            } else {
                                // All questions attempted (either correct or wrong)
                                document.getElementById('completeBtn').classList.remove('hidden');

                                // Show final stats
                                const totalCorrect = answeredQuestions.size;
                                const totalWrong = wrongAttempts.size;
                                feedback.textContent =
                                    `🎯 All questions attempted! Correct: ${totalCorrect}, Wrong: ${totalWrong}. Click "Complete Assignment" to finish.`;
                                feedback.className = 'mt-3 text-center text-lg font-semibold text-blue-600';
                                feedback.classList.remove('hidden');
                            }
                        }, 1500);
                    } else {
                        feedback.textContent = '⚠️ ' + (data.error || 'Something went wrong');
                        feedback.className = 'mt-3 text-center text-lg font-semibold text-red-600';
                        feedback.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    feedback.textContent = '⚠️ Something went wrong. Please try again.';
                    feedback.className = 'mt-3 text-center text-lg font-semibold text-red-600';
                    feedback.classList.remove('hidden');
                });

            // Reset start time for next question
            startTime = Date.now();
        }

        function updateProgress() {
            const progress = ((currentQuestionIndex) / totalQuestions) * 100;
            document.getElementById('progressBar').style.width = Math.min(progress, 100) + '%';
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                seconds++;
                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                document.getElementById('timer').textContent =
                    `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

                if (timeLimit > 0 && seconds >= timeLimit) {
                    clearInterval(timerInterval);
                    Swal.fire({
                        title: '⏰ Time\'s Up!',
                        text: 'Your time limit has been reached. The assignment will be submitted automatically.',
                        icon: 'warning',
                        confirmButtonColor: '#5C3A1E'
                    }).then(() => {
                        completeAssignment();
                    });
                }
            }, 1000);
        }

        function completeAssignment() {
            const totalAttempted = attemptedQuestions.size;

            if (totalAttempted < totalQuestions) {
                // Show info message without cancel option
                Swal.fire({
                    title: '⚠️ Not Complete',
                    text: `You have attempted ${totalAttempted} out of ${totalQuestions} questions. The assignment will be submitted now.`,
                    icon: 'info',
                    confirmButtonColor: '#5C3A1E',
                    confirmButtonText: 'OK, Submit',
                    allowOutsideClick: false
                }).then(() => {
                    finalizeAssignment();
                });
            } else {
                finalizeAssignment();
            }
        }

        function finalizeAssignment() {
            Swal.fire({
                title: '⏳ Submitting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`{{ route('student.assignments.complete', $assignment->id) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        clearInterval(timerInterval);
                        Swal.fire({
                            icon: 'success',
                            title: '✅ Assignment Complete!',
                            html: `
                                <div class="text-center">
                                    <div class="text-6xl mb-4">${data.score >= 80 ? '🌟' : data.score >= 50 ? '👍' : '💪'}</div>
                                    <p class="text-3xl font-bold text-[#5C3A1E]">${data.score}%</p>
                                    <p class="text-sm text-gray-500 mt-2">${data.correct} out of ${data.total} correct</p>
                                </div>
                            `,
                            confirmButtonColor: '#5C3A1E'
                        }).then(() => {
                            window.location.href =
                                `{{ route('student.assignments.results', $assignment->id) }}`;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Error',
                            text: data.error || 'Failed to complete assignment',
                            confirmButtonColor: '#5C3A1E'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Error',
                        text: 'Something went wrong',
                        confirmButtonColor: '#5C3A1E'
                    });
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
