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
                        <span class="text-sm">⚡ Cycle 2: Timed Challenge Mode</span>
                    </div>
                </div>

                <div class="flex justify-center items-center gap-6 mb-4 px-6">
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">⭐</span>
                        <span class="text-2xl font-bold text-[#E8A317]" id="score">0</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Stars</p>
                    </div>
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">❤️</span>
                        <span class="text-2xl font-bold text-[#E85D3A]" id="lives">3</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Lives</p>
                    </div>
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">🎯</span>
                        <span class="text-2xl font-bold text-[#5A6B2D]" id="target">100</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Target</p>
                    </div>
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner">
                        <span class="text-2xl">⏱️</span>
                        <span class="text-2xl font-bold text-[#E85D3A]" id="timerDisplay">01:00</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Time Left</p>
                    </div>
                </div>

                <div class="px-8 mb-3">
                    <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                        <span>Questions Completed</span>
                        <span id="questionCounter">0/10</span>
                    </div>
                    <div class="bg-[#E8D5A3] rounded-full h-2 overflow-hidden">
                        <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-300"
                            style="width: 0%"></div>
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
                <span class="text-3xl">⚡</span>
                <p class="text-[#5A6B2D] font-semibold text-sm md:text-base">"Answer as fast as you can! 60 seconds on the
                    clock!"</p>
            </div>
        </div>
    </div>

    @include('game.levels.level-1-5')
    @include('game.levels.level-6-10')
    @include('game.levels.level-11-15')
    @include('game.levels.level-16-20')
    @include('game.levels.level-21-25')

  <script>
    // ============================================
    // CYCLE 2: TIMED CHALLENGE MODE
    // ============================================
    
    const levelSystem = {
        ...levels1To5,
        ...levels6To10,
        ...levels11To15,
        ...levels16To20,
        ...levels21To25
    };

    const currentLevelNum = parseInt(new URLSearchParams(window.location.search).get('level')) || 1;
    const levelData = levelSystem[currentLevelNum];

    let currentQuestions = [];
    let currentIndex = 0;
    let score = 0;
    let lives = 3;
    let gameActive = true;
    let timeLeft = 60;
    let timerInterval = null;

    const answerInput = document.getElementById('answerInput');
    const answerForm = document.getElementById('answerForm');

    // SINGLE FUNCTION TO SAVE RESULTS - CALL THIS EVERYWHERE
    async function saveResults(isCompleted) {
        const totalPossible = currentQuestions.length * 15;
        const accuracy = totalPossible > 0 ? Math.round((score / totalPossible) * 100) : 0;
        
        const data = {
            level_id: currentLevelNum,
            score: score,
            accuracy: accuracy,
            time_remaining: timeLeft,
            correct_answers: currentIndex,
            is_completed: isCompleted ? 1 : 0
        };

        console.log('💾 Saving Cycle 2:', data);

        try {
            const response = await fetch('/save-cycle2', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            console.log('✅ Save result:', result);
            return result;
        } catch (error) {
            console.error('❌ Error saving:', error);
            return null;
        }
    }

    function shuffleArray(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    }

    function loadQuestions() {
        if (levelData.questions) {
            currentQuestions = shuffleArray([...levelData.questions]);
        } else {
            for (let i = 0; i < 10; i++) {
                const num1 = Math.floor(Math.random() * 11);
                const num2 = 10 - num1;
                currentQuestions.push({
                    text: `${num1} + ${num2}`,
                    answer: 10,
                    type: 'addition'
                });
            }
        }
        currentIndex = 0;
        score = 0;
        lives = 3;
        updateUI();
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            if (!gameActive) return;

            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('timerDisplay').innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 10) {
                document.getElementById('timerDisplay').style.color = '#E85D3A';
            }

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                gameActive = false;
                answerInput.disabled = true;
                handleTimeUp();
            }
        }, 1000);
    }

    function updateUI() {
        document.getElementById('score').innerHTML = score;
        document.getElementById('lives').innerHTML = lives;
        const percent = (currentIndex / currentQuestions.length) * 100;
        document.getElementById('progressBar').style.width = `${percent}%`;
        document.getElementById('questionCounter').innerHTML = `${currentIndex}/${currentQuestions.length}`;
    }

    function loadQuestion() {
        if (!gameActive) return;

        if (currentIndex >= currentQuestions.length) {
            handleComplete();
            return;
        }

        const q = currentQuestions[currentIndex];
        let displayText = q.text;
        if (q.type === 'addition' || q.type === 'addition_level2' || q.type === 'addition_up_to_20') {
            displayText = q.text + " = ?";
        }

        document.getElementById('question').innerHTML = displayText;
        answerInput.value = '';
        answerInput.disabled = false;
        answerInput.focus();
        document.getElementById('message').innerHTML = '';
    }

    answerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!gameActive) return;

        const userAnswer = parseInt(answerInput.value);
        if (isNaN(userAnswer)) {
            document.getElementById('message').innerHTML = '<span class="text-orange-500">Please type a number!</span>';
            setTimeout(() => {
                if (gameActive) document.getElementById('message').innerHTML = '';
            }, 1500);
            return;
        }

        const q = currentQuestions[currentIndex];

        if (userAnswer === q.answer) {
            score += 15;
            currentIndex++;
            document.getElementById('message').innerHTML = '<span class="text-green-600">✅ Correct! +15 points!</span>';
            updateUI();

            if (currentIndex >= currentQuestions.length) {
                handleComplete();
            } else {
                setTimeout(() => {
                    if (gameActive) document.getElementById('message').innerHTML = '';
                    loadQuestion();
                }, 800);
            }
        } else {
            lives--;
            updateUI();
            document.getElementById('message').innerHTML = `<span class="text-red-600">❌ Wrong! The answer is ${q.answer}. -1 life</span>`;

            if (lives <= 0) {
                handleGameOver();
            } else {
                setTimeout(() => {
                    if (gameActive) document.getElementById('message').innerHTML = '';
                }, 1000);
            }
        }
    });

    async function handleTimeUp() {
        gameActive = false;
        answerInput.disabled = true;
        
        // SAVE - This is an attempt, not completed
        await saveResults(false);
        
        const totalPossible = currentQuestions.length * 15;
        const accuracy = totalPossible > 0 ? Math.round((score / totalPossible) * 100) : 0;
        
        document.getElementById('message').innerHTML = `
            <div class="text-center">
                <span class="text-red-600 text-3xl block mb-3">⏰ TIME'S UP! ⏰</span>
                <div class="bg-red-50 rounded-xl p-5 mb-5">
                    <p class="text-gray-700 text-lg mb-2">You completed ${currentIndex}/${currentQuestions.length} questions!</p>
                    <p class="text-gray-700 text-lg mb-2">Your score: ${score}/${totalPossible} points</p>
                    <p class="text-gray-700 text-lg">Accuracy: ${accuracy}%</p>
                    <p class="text-green-600 text-lg mt-2">✅ Attempt saved!</p>
                </div>
                <div class="flex justify-center space-x-5 mt-4">
                    <button onclick="location.reload()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-7 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        🔄 TRY AGAIN
                    </button>
                    <button onclick="window.location.href='/game/level-details?level=${currentLevelNum}'" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-7 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        📤 BACK TO PRACTICE
                    </button>
                </div>
            </div>
        `;
        document.getElementById('message').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    async function handleGameOver() {
        gameActive = false;
        clearInterval(timerInterval);
        answerInput.disabled = true;
        
        // SAVE - This is an attempt, not completed
        await saveResults(false);
        
        const totalPossible = currentQuestions.length * 15;
        const accuracy = totalPossible > 0 ? Math.round((score / totalPossible) * 100) : 0;
        
        document.getElementById('message').innerHTML = `
            <div class="text-center">
                <span class="text-red-600 text-3xl block mb-3">💔 CHALLENGE FAILED! 💔</span>
                <div class="bg-red-50 rounded-xl p-5 mb-5">
                    <p class="text-gray-700 text-lg mb-2">You scored ${score}/${totalPossible} points!</p>
                    <p class="text-gray-700 text-lg mb-2">Accuracy: ${accuracy}%</p>
                    <p class="text-gray-700 text-lg">You completed ${currentIndex}/${currentQuestions.length} questions</p>
                    <p class="text-green-600 text-lg mt-2">✅ Attempt saved</p>
                </div>
                <div class="flex justify-center space-x-5 mt-4">
                    <button onclick="location.reload()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-7 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        🔄 TRY AGAIN
                    </button>
                    <button onclick="window.location.href='/game/level-details?level=${currentLevelNum}'" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-7 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        📤 BACK TO PRACTICE
                    </button>
                </div>
            </div>
        `;
        document.getElementById('message').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    async function handleComplete() {
        gameActive = false;
        clearInterval(timerInterval);
        answerInput.disabled = true;
        
        // SAVE - This is a completed attempt
        await saveResults(true);
        
        const totalPossible = currentQuestions.length * 15;
        const accuracy = totalPossible > 0 ? Math.round((score / totalPossible) * 100) : 0;
        
        document.getElementById('message').innerHTML = `
            <div class="text-center">
                <span class="text-green-600 text-2xl block mb-2">🏆 AMAZING! CYCLE 2 COMPLETE! 🏆</span>
                <div class="bg-green-50 rounded-xl p-4 mb-4">
                    <span class="text-gray-700 block">Questions Correct: ${currentIndex}/${currentQuestions.length}</span>
                    <span class="text-gray-700 block">Score: ${score}/${totalPossible} points</span>
                    <span class="text-gray-700 block">Accuracy: ${accuracy}%</span>
                    <span class="text-gray-700 block">Time Remaining: ${timeLeft} seconds</span>
                    <span class="text-green-600 block mt-2">✅ Results saved</span>
                </div>
                <div class="flex justify-center space-x-4 mt-4">
                    <button onclick="window.location.href='/game/cycle1?level=${currentLevelNum}'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-6 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        🔄 PLAY AGAIN
                    </button>
                    <button onclick="window.location.href='/game/levels'" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-6 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        🗺️ BACK TO LEVELS
                    </button>
                </div>
            </div>
        `;
    }

    // Initialize
    document.getElementById('levelIcon').innerHTML = levelData.icon;
    document.getElementById('levelName').innerHTML = levelData.name;
    document.getElementById('target').innerHTML = levelData.targetScore;

    loadQuestions();
    loadQuestion();
    startTimer();

    document.body.addEventListener('click', () => {
        if (gameActive && !answerInput.disabled) answerInput.focus();
    });
</script>
@endsection
