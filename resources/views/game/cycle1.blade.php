@extends('layouts.app')

@section('background')
    <div class="page-background">
        <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
            <source src="{{ asset('videos/nature-math-bg2.mp4') }}" type="video/mp4">
            <div class="absolute inset-0 bg-gradient-to-b from-[#87CEEB] via-[#98FB98] to-[#F4A623]"></div>
        </video>
        <div class="absolute inset-0 bg-black/20"></div>
    </div>
@endsection

@section('content')
    <div class="relative z-10 min-h-screen py-8 px-4">

        <div class="max-w-4xl mx-auto mb-6">
            <a href="/game/level-details?level={{ request('level') }}"
                class="inline-flex items-center space-x-2 bg-white/90 backdrop-blur-sm hover:bg-white px-4 py-2 rounded-full transition-all shadow-md">
                <span>←</span>
                <span>Back to Level Details</span>
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-[#FDF8F0] rounded-3xl border-4 border-[#E8D5A3] shadow-2xl overflow-hidden">

                <div class="h-3 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>

                <div class="text-center pt-6 pb-2 px-6">
                    <div class="inline-flex items-center space-x-3 bg-[#F0E8D0] rounded-full px-6 py-2 shadow-inner">
                        <span class="text-2xl" id="levelIcon">🌱</span>
                        <span class="text-xl font-bold text-[#5A6B2D]" id="levelName">Loading...</span>
                    </div>
                </div>

                <div class="text-center mb-2">
                    <div class="inline-flex items-center space-x-2 bg-[#E8F0E0] rounded-full px-4 py-1">
                        <span class="text-sm">🔄 Cycle 1: Practice Mode</span>
                    </div>
                </div>

                <div class="flex justify-center items-center gap-6 mb-4 px-6">
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">⭐</span>
                        <span class="text-2xl font-bold text-[#E8A317]" id="score">0</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Points</p>
                    </div>
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">✅</span>
                        <span class="text-2xl font-bold text-[#5A6B2D]" id="correctCount">0</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Correct</p>
                    </div>
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">❌</span>
                        <span class="text-2xl font-bold text-[#E85D3A]" id="wrongCount">0</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Wrong</p>
                    </div>
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">⏱️</span>
                        <span class="text-2xl font-bold text-[#E85D3A]" id="timerDisplay">01:00</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Time Left</p>
                    </div>
                </div>

                <div class="mx-6 mb-4">
                    <div class="bg-[#E8F0E0] rounded-2xl p-6 text-center shadow-inner border border-[#D0E0B8]">
                        <div class="text-5xl md:text-6xl font-bold mb-2 text-[#5A6B2D]" id="question">
                            Loading...
                        </div>
                        <div id="answerInputArea" class="mt-4">
                            <form id="answerForm" onsubmit="return false;">
                                <input type="number" id="answerInput"
                                    class="text-center text-3xl font-bold text-[#5A6B2D] bg-white border-2 border-[#E8D5A3] rounded-xl p-3 w-40 mx-auto"
                                    placeholder="?" autofocus>
                            </form>
                            <p class="text-sm text-[#8C9DB3] mt-2">Type your answer and press Enter</p>
                        </div>
                    </div>
                </div>

                <div class="pb-6 px-6 text-center">
                    <div id="message" class="text-lg font-bold min-h-[70px]"></div>
                </div>

                <div class="h-2 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>
            </div>
        </div>

        <div class="text-center mt-6">
            <div
                class="inline-flex items-center space-x-3 bg-[#FDF8F0]/90 rounded-full px-5 py-2 shadow-lg border border-[#E8D5A3]">
                <span class="text-3xl">🦉</span>
                <p class="text-[#5A6B2D] font-semibold text-sm md:text-base">"Answer as many questions as you can in 30
                    seconds!"</p>
            </div>
        </div>
    </div>

    @include('game.levels.level-1-5')
    @include('game.levels.level-6-10')
    @include('game.levels.level-11-15')

    <script>
        // ============================================
        // CYCLE 1: PRACTICE MODE - UNLIMITED QUESTIONS
        // Answer as many as you can in 30 seconds
        // ============================================

        const levelSystem = {
            ...levels1To5,
            ...levels6To10,
            ...levels11To15 
        };

        const currentLevelNum = parseInt(new URLSearchParams(window.location.search).get('level')) || 1;
        const levelData = levelSystem[currentLevelNum];

        let currentQuestion = null;
        let currentQuestionObj = null;
        let score = 0;
        let correctCount = 0;
        let wrongCount = 0;
        let gameActive = true;
        let timeLeft = 30;
        let timerInterval = null;

        const answerInput = document.getElementById('answerInput');
        const answerForm = document.getElementById('answerForm');

        // Get questions from level files
        let levelQuestions = [];

        // Load questions from levelData
        function loadLevelQuestions() {
            if (levelData.questions && levelData.questions.length > 0) {
                levelQuestions = [...levelData.questions];
                console.log(`Loaded ${levelQuestions.length} questions from level files`);
            } else {
                // Fallback: generate random questions if no questions in levelData
                console.warn('No questions found in levelData, using fallback generation');
                levelQuestions = generateFallbackQuestions();
            }
        }

        // Fallback question generation (only used if level files don't have questions)
        function generateFallbackQuestions() {
            const questions = [];
            const levelNum = currentLevelNum;

            for (let i = 0; i < 50; i++) { // Generate 50 fallback questions
                if (levelNum === 1) {
                    const pairs = [
                        [1, 9],
                        [2, 8],
                        [3, 7],
                        [4, 6],
                        [5, 5],
                        [6, 4],
                        [7, 3],
                        [8, 2],
                        [9, 1],
                        [0, 10]
                    ];
                    const pair = pairs[Math.floor(Math.random() * pairs.length)];
                    questions.push({
                        text: pair[0] + ' + ' + pair[1],
                        answer: 10,
                        type: 'addition'
                    });
                } else if (levelNum === 2) {
                    let num1 = Math.floor(Math.random() * 10);
                    let maxNum2 = 9 - num1;
                    let num2 = maxNum2 >= 0 ? Math.floor(Math.random() * (maxNum2 + 1)) : 0;
                    questions.push({
                        text: num1 + ' + ' + num2,
                        answer: num1 + num2,
                        type: 'addition_level2'
                    });
                } else if (levelNum === 3) {
                    if (Math.random() > 0.5) {
                        const pairs = [
                            [1, 9],
                            [2, 8],
                            [3, 7],
                            [4, 6],
                            [5, 5]
                        ];
                        const pair = pairs[Math.floor(Math.random() * pairs.length)];
                        questions.push({
                            text: pair[0] + ' + ' + pair[1],
                            answer: 10,
                            type: 'addition'
                        });
                    } else {
                        let num1 = Math.floor(Math.random() * 11);
                        let maxNum2 = 10 - num1;
                        let num2 = maxNum2 >= 0 ? Math.floor(Math.random() * (maxNum2 + 1)) : 0;
                        questions.push({
                            text: num1 + ' + ' + num2,
                            answer: num1 + num2,
                            type: 'addition_level2'
                        });
                    }
                } else if (levelNum === 4) {
                    let num = Math.floor(Math.random() * 9) + 1;
                    questions.push({
                        text: '10 + ' + num,
                        answer: 10 + num,
                        type: 'teen_addition'
                    });
                } else if (levelNum === 5) {
                    let num1 = Math.floor(Math.random() * 21);
                    let maxNum2 = 20 - num1;
                    let num2 = maxNum2 >= 0 ? Math.floor(Math.random() * (maxNum2 + 1)) : 0;
                    questions.push({
                        text: num1 + ' + ' + num2,
                        answer: num1 + num2,
                        type: 'addition_up_to_20'
                    });
                } else {
                    let num1 = Math.floor(Math.random() * 11);
                    let num2 = Math.floor(Math.random() * 11);
                    questions.push({
                        text: num1 + ' + ' + num2,
                        answer: num1 + num2,
                        type: 'addition'
                    });
                }
            }
            return questions;
        }

        // Get a random question from the pool (allows repeats since it's unlimited)
        function getRandomQuestion() {
            if (levelQuestions.length > 0) {
                // Pick a random question from the pool
                const randomIndex = Math.floor(Math.random() * levelQuestions.length);
                const q = levelQuestions[randomIndex];
                return {
                    text: q.text,
                    answer: q.answer
                };
            }
            return null;
        }

        function loadQuestion() {
            if (!gameActive) return;

            currentQuestion = getRandomQuestion();

            if (!currentQuestion) {
                console.error('No questions available!');
                return;
            }

            // Format display text
            let displayText = currentQuestion.text;
            // Add = ? for addition type questions
            if (!displayText.includes('=')) {
                displayText = currentQuestion.text + " = ⬜";
            }

            document.getElementById('question').innerHTML = displayText;
            answerInput.value = '';
            answerInput.disabled = false;
            answerInput.focus();
            document.getElementById('message').innerHTML = '';
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                if (!gameActive) return;

                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                document.getElementById('timerDisplay').innerHTML =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 10) {
                    document.getElementById('timerDisplay').style.color = '#E85D3A';
                }

                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    gameActive = false;
                    answerInput.disabled = true;
                    showTimeUp();
                }
            }, 1000);
        }

        function updateStats() {
            document.getElementById('score').innerHTML = score;
            document.getElementById('correctCount').innerHTML = correctCount;
            document.getElementById('wrongCount').innerHTML = wrongCount;
        }

        async function saveResults() {
            const data = {
                level_id: currentLevelNum,
                questions_answered: correctCount + wrongCount,
                time_limit: 30 - timeLeft,
                correct_answers: correctCount,
                wrong_answers: wrongCount
            };

            console.log('Saving Cycle 1:', data);

            try {
                const response = await fetch('/save-cycle1', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                console.log('Save result:', result);
            } catch (error) {
                console.error('Error saving:', error);
            }
        }

        function showTimeUp() {
            const totalAttempted = correctCount + wrongCount;
            const accuracy = totalAttempted > 0 ? Math.round((correctCount / totalAttempted) * 100) : 0;

            document.getElementById('message').innerHTML = `
            <div class="text-center">
                <span class="text-orange-600 text-3xl block mb-3">⏰ TIME'S UP! ⏰</span>
                <div class="bg-orange-50 rounded-xl p-5 mb-5">
                    <p class="text-gray-700 text-lg mb-2">You answered ${totalAttempted} questions!</p>
                    <p class="text-gray-700 text-lg mb-2">✅ Correct: ${correctCount}</p>
                    <p class="text-gray-700 text-lg mb-2">❌ Wrong: ${wrongCount}</p>
                    <p class="text-gray-700 text-lg">Accuracy: ${accuracy}%</p>
                </div>
                <div class="flex justify-center space-x-5 mt-4">
                    <button onclick="location.reload()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-7 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        🔄 TRY AGAIN
                    </button>
                    <button onclick="window.location.href='/game/level-details?level=${currentLevelNum}'" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-7 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        📤 LEAVE
                    </button>
                </div>
            </div>
        `;

            saveResults();
            document.getElementById('message').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        answerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!gameActive) return;

            const userAnswer = parseInt(answerInput.value);
            if (isNaN(userAnswer)) {
                document.getElementById('message').innerHTML =
                    '<span class="text-orange-500">Please type a number!</span>';
                setTimeout(() => {
                    if (gameActive) document.getElementById('message').innerHTML = '';
                }, 1500);
                return;
            }

            if (userAnswer === currentQuestion.answer) {
                score += 10;
                correctCount++;
                document.getElementById('message').innerHTML =
                    '<span class="text-green-600">✅ Correct! +10 points!</span>';
                updateStats();

                setTimeout(() => {
                    if (gameActive) document.getElementById('message').innerHTML = '';
                    loadQuestion();
                }, 300);
            } else {
                wrongCount++;
                document.getElementById('message').innerHTML =
                    `<span class="text-red-600">❌ Wrong! The answer is ${currentQuestion.answer}</span>`;
                updateStats();

                setTimeout(() => {
                    if (gameActive) document.getElementById('message').innerHTML = '';
                    loadQuestion();
                }, 800);
            }
        });

        // Initialize
        document.getElementById('levelIcon').innerHTML = levelData.icon;
        document.getElementById('levelName').innerHTML = levelData.name;

        // Load questions from level files first
        loadLevelQuestions();

        // Log for debugging
        console.log(`Level ${currentLevelNum} has ${levelQuestions.length} questions loaded`);
        console.log('Sample question:', levelQuestions[0]);

        loadQuestion();
        startTimer();

        document.body.addEventListener('click', () => {
            if (gameActive && !answerInput.disabled) answerInput.focus();
        });
    </script>
@endsection