@extends('layouts.student')

@section('content')
    <div class="relative z-10 min-h-screen py-8 px-4">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-black flex items-center gap-3">
                        <span>📝</span>
                        <span>{{ $assignment->questionSet->title }}</span>
                    </h1>
                    <p class="text-gray-600 text-sm mt-1">{{ $assignment->questionSet->description ?? 'No description' }}</p>
                </div>
                <div class="flex items-center gap-3 mt-4 md:mt-0">
                    <a href="{{ route('student.assignments.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2"
                        id="backButton">
                        <span>⬅️</span> Back
                    </a>
                    <div id="timerDisplay"
                        class="bg-gray-200 text-black px-6 py-3 rounded-xl text-lg font-bold shadow-lg border-2 border-gray-300">
                        ⏱️ <span id="timer" class="text-2xl font-mono">00:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-4 shadow-xl text-center border border-gray-200">
                <p class="text-gray-500 text-xs uppercase tracking-wider">Question</p>
                <p class="text-2xl font-bold text-black">
                    <span id="currentQuestionNum">1</span> / <span
                        id="totalQuestions">{{ $assignment->questionSet->questions->count() }}</span>
                </p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-xl text-center border border-gray-200">
                <p class="text-gray-500 text-xs uppercase tracking-wider">Correct</p>
                <p class="text-2xl font-bold text-green-600">
                    ✅ <span id="correctCount">0</span>
                </p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-xl text-center border border-gray-200">
                <p class="text-gray-500 text-xs uppercase tracking-wider">Progress</p>
                <p class="text-2xl font-bold text-black">
                    <span id="progressPercent">0</span>%
                </p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-xl mb-6 border border-gray-200">
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div id="progressBar" class="bg-[#5C3A1E] h-4 rounded-full transition-all duration-500" style="width: 0%">
                </div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="bg-white rounded-2xl p-10 shadow-xl max-w-4xl mx-auto border border-gray-200">
            <div id="questionContainer">
                <div class="text-center text-gray-400 py-8">
                    Loading question...
                </div>
            </div>

            <!-- Answer Input -->
            <div class="mt-8" id="answerSection">
                <label class="block text-lg font-semibold text-black mb-3">Your Answer:</label>
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="number" id="answerInput"
                        class="flex-1 px-6 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-2xl text-black"
                        placeholder="Type your answer..." autofocus>
                    <button id="submitBtn"
                        class="bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-10 py-4 rounded-xl font-bold text-xl transition-all shadow-lg min-w-[150px]">
                        Submit
                    </button>
                </div>
                <div id="feedback" class="mt-4 text-center text-xl font-semibold hidden"></div>
            </div>

            <!-- Next Question Button -->
            <div class="mt-6 text-center">
                <button id="nextBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold text-xl transition-all shadow-lg hidden">
                    ➡️ Next Question
                </button>
            </div>

            <!-- Complete Button -->
            <div class="mt-10 text-center">
                <button id="completeBtn"
                    class="bg-green-600 hover:bg-green-700 text-white px-10 py-4 rounded-xl font-bold text-xl transition-all shadow-lg hidden">
                    ✅ Complete Assignment
                </button>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ============================================
        // DECLARE ALL VARIABLES
        // ============================================
        let currentQuestionIndex = {{ $currentIndex ?? 0 }};
        let questions = @json($assignment->questionSet->questions);
        let totalQuestions = questions.length;
        let correctCount = 0;
        let answeredQuestions = new Set();
        let wrongAttempts = new Set();
        let attemptedQuestions = new Set();
        let timerInterval = null;
        let timeRemaining = {{ $timeRemaining ?? 0 }};
        let timeSpent = {{ $timeSpent ?? 0 }};
        let startTime = Date.now();
        let operationType = @json($assignment->questionSet->operation_type ?? 'addition');
        let isLoadingAttempts = false;
        let isInitialized = false;
        let autoSaveInterval = null;
        let lastSavedTimeSpent = timeSpent;
        let isSaving = false;

        const assignmentId = {{ $assignment->id }};
        const timeLimit = {{ $assignment->questionSet->time_limit ?? 0 }};
        const submitUrl = '{{ route('student.assignments.submit') }}';
        const completeUrl = '{{ route('student.assignments.complete', $assignment->id) }}';
        const resultsUrl = '{{ route('student.assignments.results', $assignment->id) }}';
        const attemptsUrl = '{{ route('student.assignments.attempts', $assignment->id) }}';
        const progressUrl = '{{ route('student.assignments.progress', $assignment->id) }}';
        const saveProgressUrl = '{{ route('student.assignments.save-progress', $assignment->id) }}';

        // ============================================
        // AUTO-SAVE PROGRESS EVERY 3 SECONDS
        // ============================================
        function startAutoSave() {
            if (autoSaveInterval) clearInterval(autoSaveInterval);

            autoSaveInterval = setInterval(() => {
                if (timeSpent !== lastSavedTimeSpent && !isSaving) {
                    saveProgress();
                }
            }, 3000);
        }

        function saveProgress() {
            if (isSaving) return;
            isSaving = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const data = {
                time_spent: timeSpent,
                current_question_index: currentQuestionIndex
            };

            fetch(saveProgressUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        lastSavedTimeSpent = timeSpent;
                    }
                })
                .catch(error => console.error('Auto-save error:', error))
                .finally(() => {
                    isSaving = false;
                });
        }

        function saveProgressOnExit() {
            if (timeSpent !== lastSavedTimeSpent) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const data = {
                    time_spent: timeSpent,
                    current_question_index: currentQuestionIndex
                };
                navigator.sendBeacon(saveProgressUrl, new Blob(
                    [JSON.stringify(data)], {
                        type: 'application/json'
                    }
                ));
            }
        }

        // ============================================
        // INITIALIZE
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            lastSavedTimeSpent = timeSpent;

            if (timeLimit > 0) {
                document.getElementById('timerDisplay').classList.remove('hidden');
                updateTimerDisplay();
                startTimer();
                startAutoSave();
            }

            loadPreviousAttempts().then(() => {
                renderQuestion(currentQuestionIndex);
                updateProgress();
                isInitialized = true;
            });

            window.addEventListener('beforeunload', saveProgressOnExit);
            window.addEventListener('pagehide', saveProgressOnExit);

            document.getElementById('backButton').addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                saveProgress();
                Swal.fire({
                    title: '⚠️ Leave Assignment?',
                    text: 'Your progress will be saved. You can continue later.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5C3A1E',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, leave',
                    cancelButtonText: 'Stay'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (timeSpent !== lastSavedTimeSpent) {
                            saveProgress();
                        }
                        window.location.href = href;
                    }
                });
            });

            document.getElementById('answerInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    submitAnswer();
                }
            });

            document.getElementById('submitBtn').addEventListener('click', submitAnswer);
            document.getElementById('nextBtn').addEventListener('click', goToNextQuestion);
            document.getElementById('completeBtn').addEventListener('click', completeAssignment);
        });

        // ============================================
        // LOAD PREVIOUSLY ATTEMPTED QUESTIONS
        // ============================================
        function loadPreviousAttempts() {
            if (isLoadingAttempts) return Promise.resolve();
            isLoadingAttempts = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            return fetch(attemptsUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to load attempts');
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.attempts) {
                        correctCount = 0;
                        answeredQuestions.clear();
                        wrongAttempts.clear();
                        attemptedQuestions.clear();

                        data.attempts.forEach(attempt => {
                            attemptedQuestions.add(attempt.question_id);
                            if (attempt.is_correct) {
                                answeredQuestions.add(attempt.question_id);
                                correctCount++;
                            } else {
                                wrongAttempts.add(attempt.question_id);
                            }
                        });

                        document.getElementById('correctCount').textContent = correctCount;
                    }
                    return data;
                })
                .catch(error => console.error('Failed to load attempts:', error))
                .finally(() => {
                    isLoadingAttempts = false;
                });
        }

        // ============================================
        // RENDER QUESTION
        // ============================================
        function renderQuestion(index) {
            if (!questions || questions.length === 0 || index >= questions.length) {
                return;
            }

            const question = questions[index];
            const container = document.getElementById('questionContainer');
            const isLast = index === totalQuestions - 1;

            let displayText = question.display || question.display_text || '';

            if (!displayText) {
                const separator = operationType === 'subtraction' ? ' - ' : ' + ';
                if (question.type === 'two_numbers') {
                    displayText = `${question.num1}${separator}${question.num2} = ?`;
                } else if (question.type === 'three_numbers') {
                    displayText = `${question.num1}${separator}${question.num2}${separator}${question.num3} = ?`;
                } else if (question.type === 'missing_number') {
                    const parts = [question.num1, question.num2, question.num3];
                    const displayParts = parts;
                    displayParts[question.missing_position - 1] = '?';
                    displayText = displayParts.join(separator) + ` = ${question.answer}`;
                }
            }

            container.innerHTML = `
                <div class="text-center">
                    <span class="text-sm text-gray-500 bg-gray-100 px-4 py-1 rounded-full">Q${index + 1}</span>
                    <div class="text-5xl md:text-6xl font-bold text-black mt-6 py-4">
                        ${displayText}
                    </div>
                    <div class="mt-3 text-sm text-gray-500">
                        Type: ${question.type ? question.type.replace('_', ' ') : 'Unknown'}
                    </div>
                </div>
            `;

            document.getElementById('currentQuestionNum').textContent = index + 1;

            document.getElementById('answerInput').value = '';
            document.getElementById('feedback').classList.add('hidden');
            document.getElementById('feedback').textContent = '';
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('completeBtn').classList.add('hidden');

            if (attemptedQuestions.has(question.id)) {
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('answerInput').disabled = true;

                if (answeredQuestions.has(question.id)) {
                    document.getElementById('feedback').classList.remove('hidden');
                    document.getElementById('feedback').textContent = '✅ Already answered correctly!';
                    document.getElementById('feedback').className = 'mt-4 text-center text-xl font-semibold text-green-600';
                } else if (wrongAttempts.has(question.id)) {
                    document.getElementById('feedback').classList.remove('hidden');
                    document.getElementById('feedback').textContent = '❌ You already answered this incorrectly.';
                    document.getElementById('feedback').className = 'mt-4 text-center text-xl font-semibold text-red-600';
                }

                if (!isLast) {
                    document.getElementById('nextBtn').classList.remove('hidden');
                    document.getElementById('nextBtn').textContent = '➡️ Next Question';
                    document.getElementById('nextBtn').focus();
                } else {
                    document.getElementById('completeBtn').classList.remove('hidden');
                }
            } else {
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('answerInput').disabled = false;
                document.getElementById('answerInput').focus();
            }
        }

        // ============================================
        // GO TO NEXT QUESTION
        // ============================================
        function goToNextQuestion() {
            if (currentQuestionIndex < totalQuestions - 1) {
                currentQuestionIndex++;
                renderQuestion(currentQuestionIndex);
                updateProgress();
                document.getElementById('nextBtn').classList.add('hidden');
            }
        }

        // ============================================
        // SUBMIT ANSWER - WITH SOUND EFFECTS
        // ============================================
        function submitAnswer() {
            if (!questions || questions.length === 0 || currentQuestionIndex >= questions.length) {
                return;
            }

            const question = questions[currentQuestionIndex];
            const answerInput = document.getElementById('answerInput');
            const answer = parseInt(answerInput.value);
            const feedback = document.getElementById('feedback');
            const isLast = currentQuestionIndex === totalQuestions - 1;

            if (isNaN(answer)) {
                feedback.textContent = '⚠️ Please enter a number';
                feedback.className = 'mt-4 text-center text-xl font-semibold text-red-600';
                feedback.classList.remove('hidden');
                return;
            }

            if (attemptedQuestions.has(question.id)) {
                feedback.textContent = '⚠️ You already answered this question!';
                feedback.className = 'mt-4 text-center text-xl font-semibold text-yellow-600';
                feedback.classList.remove('hidden');
                return;
            }

            const timeTaken = Math.floor((Date.now() - startTime) / 1000);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const requestData = {
                assignment_id: assignmentId,
                question_id: question.id,
                student_answer: answer,
                time_taken: timeTaken,
                current_index: currentQuestionIndex + 1
            };

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').textContent = 'Submit';

            // ✅ Play click sound when submitting
            if (window.playSound) {
                window.playSound('click');
            }

            fetch(submitUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(async response => {
                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        attemptedQuestions.add(question.id);

                        if (data.is_correct) {
                            correctCount++;
                            answeredQuestions.add(question.id);
                            feedback.textContent = '✅ Correct! Well done! 🎉';
                            feedback.className = 'mt-4 text-center text-xl font-semibold text-green-600';
                            document.getElementById('correctCount').textContent = correctCount;

                            // ✅ Play SUCCESS sound
                            if (window.triggerCorrect) {
                                window.triggerCorrect('Assignment');
                            }
                        } else {
                            wrongAttempts.add(question.id);
                            feedback.textContent = '❌ Incorrect. The correct answer was ' + data.correct_answer + '.';
                            feedback.className = 'mt-4 text-center text-xl font-semibold text-red-600';

                            // ❌ Play ERROR sound
                            if (window.triggerWrong) {
                                window.triggerWrong('Assignment');
                            }
                        }

                        document.getElementById('submitBtn').disabled = true;
                        document.getElementById('answerInput').disabled = true;
                        feedback.classList.remove('hidden');

                        lastSavedTimeSpent = timeSpent;

                        if (!isLast) {
                            document.getElementById('nextBtn').classList.remove('hidden');
                            document.getElementById('nextBtn').textContent = '➡️ Next Question';
                            document.getElementById('nextBtn').focus();
                        } else {
                            document.getElementById('completeBtn').classList.remove('hidden');

                            const totalCorrect = answeredQuestions.size;
                            const totalWrong = wrongAttempts.size;

                            feedback.innerHTML = `
    <div class="bg-white rounded-2xl border-2 border-blue-200 p-6 mt-2 shadow-lg">
        <div class="text-center">
            <div class="text-5xl mb-2">🎯</div>
            <div class="text-xl font-bold text-gray-800 mb-3">All questions attempted!</div>
            <div class="flex justify-center gap-8">
                <div>
                    <div class="text-3xl font-bold text-green-600">${totalCorrect}</div>
                    <div class="text-sm text-gray-500">✅ Correct</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-red-500">${totalWrong}</div>
                    <div class="text-sm text-gray-500">❌ Wrong</div>
                </div>
            </div>
            <div class="mt-3 text-sm text-blue-600 font-semibold">
                Click "Complete Assignment" to finish ✅
            </div>
        </div>
    </div>
`;
                            feedback.className = 'mt-4 text-center';
                            feedback.classList.remove('hidden');
                        }
                    } else {
                        if (data.already_attempted) {
                            attemptedQuestions.add(question.id);
                            feedback.textContent = '⚠️ You already answered this question.';
                            feedback.className = 'mt-4 text-center text-xl font-semibold text-yellow-600';
                            feedback.classList.remove('hidden');
                            document.getElementById('submitBtn').disabled = true;
                            document.getElementById('answerInput').disabled = true;

                            if (!isLast) {
                                document.getElementById('nextBtn').classList.remove('hidden');
                                document.getElementById('nextBtn').textContent = '➡️ Next Question';
                            } else {
                                document.getElementById('completeBtn').classList.remove('hidden');
                            }
                        } else {
                            feedback.textContent = '⚠️ ' + (data.error || data.message || 'Something went wrong');
                            feedback.className = 'mt-4 text-center text-xl font-semibold text-red-600';
                            feedback.classList.remove('hidden');
                            document.getElementById('submitBtn').disabled = false;
                            document.getElementById('submitBtn').textContent = 'Submit';
                        }
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    feedback.textContent = '⚠️ Error: ' + error.message;
                    feedback.className = 'mt-4 text-center text-xl font-semibold text-red-600';
                    feedback.classList.remove('hidden');
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('submitBtn').textContent = 'Submit';
                });

            startTime = Date.now();
        }

        // ============================================
        // UPDATE PROGRESS
        // ============================================
        function updateProgress() {
            const progress = totalQuestions > 0 ? ((currentQuestionIndex) / totalQuestions) * 100 : 0;
            document.getElementById('progressBar').style.width = Math.min(progress, 100) + '%';
            document.getElementById('progressPercent').textContent = Math.min(Math.round(progress), 100);
        }

        // ============================================
        // TIMER FUNCTIONS
        // ============================================
        function updateTimerDisplay() {
            const mins = Math.floor(timeRemaining / 60);
            const secs = timeRemaining % 60;
            document.getElementById('timer').textContent =
                `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

            const timerElement = document.getElementById('timer');
            if (timeRemaining <= 10) {
                timerElement.className = 'text-2xl font-mono text-red-600 animate-pulse';
            } else if (timeRemaining <= 30) {
                timerElement.className = 'text-2xl font-mono text-yellow-600';
            } else {
                timerElement.className = 'text-2xl font-mono text-black';
            }
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);

            timerInterval = setInterval(() => {
                timeRemaining--;
                timeSpent++;
                updateTimerDisplay();

                if (timeSpent - lastSavedTimeSpent >= 5) {
                    saveProgress();
                }

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    clearInterval(autoSaveInterval);
                    saveProgress();

                    Swal.fire({
                        title: '⏰ Time\'s Up!',
                        text: 'Your time limit has been reached. The assignment will be submitted automatically.',
                        icon: 'warning',
                        confirmButtonColor: '#5C3A1E',
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        completeAssignment();
                    });
                }
            }, 1000);
        }

        // ============================================
        // COMPLETE ASSIGNMENT
        // ============================================
        function completeAssignment() {
            const totalAttempted = attemptedQuestions.size;

            if (totalAttempted < totalQuestions) {
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
            saveProgress();

            Swal.fire({
                title: 'Submit',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(completeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        clearInterval(timerInterval);
                        clearInterval(autoSaveInterval);

                        // ✅ Play success sound on completion
                        if (window.triggerComplete) {
                            window.triggerComplete('Assignment');
                        }

                        Swal.fire({
                            icon: 'success',
                            title: '✅ Assignment Complete!',
                            html: `
                            <div class="text-center">
                                <div class="text-6xl mb-4">${data.score >= 80 ? '🌟' : data.score >= 50 ? '👍' : '💪'}</div>
                                <p class="text-3xl font-bold text-[#5C3A1E]">${data.score}%</p>
                                <p class="text-sm text-gray-500 mt-2">${data.correct} out of ${data.total} correct</p>
                                <p class="text-sm text-gray-400 mt-1">Time taken: ${data.time_taken || 'N/A'}</p>
                            </div>
                        `,
                            confirmButtonColor: '#5C3A1E'
                        }).then(() => {
                            window.location.href = resultsUrl;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '❌ Error',
                            text: data.error || data.message || 'Failed to complete assignment',
                            confirmButtonColor: '#5C3A1E'
                        });
                    }
                })
                .catch(error => {
                    console.error('Complete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Error',
                        text: 'Something went wrong: ' + error.message,
                        confirmButtonColor: '#5C3A1E'
                    });
                });
        }
    </script>
@endsection
