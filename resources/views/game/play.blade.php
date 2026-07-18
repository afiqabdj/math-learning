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
        <a href="/game/levels" class="inline-flex items-center space-x-2 bg-white/90 backdrop-blur-sm hover:bg-white px-4 py-2 rounded-full transition-all shadow-md">
            <span>←</span>
            <span>Back to Trail Map</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-[#FDF8F0] rounded-3xl border-4 border-[#E8D5A3] shadow-2xl overflow-hidden">
            
            <div class="h-3 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>

            <div class="text-center pt-6 pb-2 px-6">
                <div class="inline-flex items-center space-x-3 bg-[#F0E8D0] rounded-full px-6 py-2 shadow-inner">
                    <span class="text-2xl" id="levelIcon">🌱</span>
                    <span class="text-xl font-bold text-[#5A6B2D]" id="levelName">Level 1</span>
                </div>
            </div>

            <div class="text-center mb-2">
                <div class="inline-flex items-center space-x-2 bg-[#E8F0E0] rounded-full px-4 py-1">
                    <span class="text-sm" id="cycleText">Cycle 1: Practice Mode</span>
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
                    <span class="text-2xl font-bold" id="timerDisplay">00:00</span>
                    <p class="text-xs text-[#7A8B3B] font-semibold" id="timerLabel">Time Elapsed</p>
                </div>
            </div>

            <div class="px-8 mb-3">
                <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                    <span>Questions Completed</span>
                    <span id="questionCounter">0/10</span>
                </div>
                <div class="bg-[#E8D5A3] rounded-full h-2 overflow-hidden">
                    <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <div class="mx-6 mb-4">
                <div class="bg-[#E8F0E0] rounded-2xl p-6 text-center shadow-inner border border-[#D0E0B8]">
                    <div class="text-5xl md:text-6xl font-bold mb-2 text-[#5A6B2D]" id="question">
                        Loading...
                    </div>
                    <div id="answerInputArea" class="mt-4">
                        <form id="answerForm" onsubmit="return false;">
                            <input type="number" id="answerInput" class="text-center text-3xl font-bold text-[#5A6B2D] bg-white border-2 border-[#E8D5A3] rounded-xl p-3 w-40 mx-auto" placeholder="?" autofocus>
                        </form>
                        <p class="text-sm text-[#8C9DB3] mt-2" id="inputHint">Type your answer and press Enter</p>
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
        <div class="inline-flex items-center space-x-3 bg-[#FDF8F0]/90 rounded-full px-5 py-2 shadow-lg border border-[#E8D5A3]">
            <span class="text-3xl" id="helperIcon">🦉</span>
            <p class="text-[#5A6B2D] font-semibold text-sm md:text-base" id="helperMessage">"Complete Cycle 1 to unlock the timed challenge!"</p>
        </div>
    </div>
</div>

@include('game.levels.level-1-5')
@include('game.levels.level-6-10')
@include('game.levels.level-11-15')
@include('game.levels.level-16-20')

<script>
    // ============================================
    // DUAL CYCLE GAME SYSTEM WITH DATABASE SAVING
    // ============================================
    
    const levelSystem = {
        ...levels1To5,
        ...levels6To10,
        ...levels11To15,
        ...levels16To20
    };

    // Game state
    let currentCycle = 1;
    let currentLevelNum = parseInt(new URLSearchParams(window.location.search).get('level')) || 1;
    let levelData = levelSystem[currentLevelNum];
    
    let currentQuestions = [];
    let currentIndex = 0;
    let score = 0;
    let lives = 3;
    let gameActive = true;
    let cycleCompleted = false;
    let isCompleting = false;
    
    // Timer variables
    let practiceTimerInterval = null;
    let practiceSeconds = 0;
    let challengeTimerInterval = null;
    let challengeTimeLeft = 60;
    
    // DOM Elements
    const answerInput = document.getElementById('answerInput');
    const answerForm = document.getElementById('answerForm');
    const timerDisplaySpan = document.getElementById('timerDisplay');
    const timerLabel = document.getElementById('timerLabel');
    const cycleText = document.getElementById('cycleText');
    const helperMessage = document.getElementById('helperMessage');
    const helperIcon = document.getElementById('helperIcon');
    
    // Save game results to database
    function saveGameResults(cycle, score, accuracy, timeSpent, timeRemaining) {
        const data = {
            level_id: currentLevelNum,
            score: score,
            accuracy: accuracy
        };
        
        if (cycle === 1) {
            data.time_spent = timeSpent;
            var url = '/api/game/cycle1/save';
        } else {
            data.time_remaining = timeRemaining;
            var url = '/api/game/cycle2/save';
        }
        
        console.log('Saving to database:', data);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Progress saved successfully for Cycle ' + cycle);
            } else {
                console.error('Save failed:', data);
            }
        })
        .catch(error => console.error('Error saving progress:', error));
    }
    
    function loadQuestions() {
        if (levelData.questions) {
            currentQuestions = [...levelData.questions];
        } else {
            currentQuestions = generateRandomQuestions();
        }
        currentIndex = 0;
        score = 0;
        lives = 3;
        updateScore();
        updateLives();
        updateProgress();
    }
    
    function generateRandomQuestions() {
        const questions = [];
        for (let i = 0; i < 10; i++) {
            const num1 = Math.floor(Math.random() * 11);
            const num2 = 10 - num1;
            questions.push({
                text: `${num1} + ${num2}`,
                answer: 10,
                type: 'addition'
            });
        }
        return questions;
    }
    
    function startPracticeMode() {
        currentCycle = 1;
        cycleText.innerHTML = 'Cycle 1: Practice Mode';
        cycleText.className = 'text-sm bg-green-100 text-green-600 px-3 py-1 rounded-full';
        timerLabel.innerHTML = 'Time Elapsed';
        helperMessage.innerHTML = '"Complete Cycle 1 to unlock the timed challenge!"';
        helperIcon.innerHTML = '🦉';
        answerInput.disabled = false;
        answerInput.value = '';
        
        currentIndex = 0;
        score = 0;
        lives = 3;
        gameActive = true;
        cycleCompleted = false;
        practiceSeconds = 0;
        updateTimerDisplay('practice');
        
        if (practiceTimerInterval) clearInterval(practiceTimerInterval);
        practiceTimerInterval = setInterval(() => {
            if (gameActive && currentCycle === 1) {
                practiceSeconds++;
                updateTimerDisplay('practice');
            }
        }, 1000);
        
        if (levelData.questions) {
            currentQuestions = [...levelData.questions];
        }
        
        updateScore();
        updateLives();
        updateProgress();
        loadCurrentQuestion();
        answerInput.focus();
    }
    
    function startChallengeMode() {
        if (practiceTimerInterval) clearInterval(practiceTimerInterval);
        
        currentCycle = 2;
        cycleText.innerHTML = '⚡ Cycle 2: Timed Challenge Mode ⚡';
        cycleText.className = 'text-sm bg-red-100 text-red-600 px-3 py-1 rounded-full';
        timerLabel.innerHTML = 'Time Left';
        helperMessage.innerHTML = '"Type the answers as fast as you can! Use your keyboard!"';
        helperIcon.innerHTML = '⚡';
        
        currentIndex = 0;
        score = 0;
        lives = 3;
        challengeTimeLeft = 60;
        gameActive = true;
        cycleCompleted = false;
        updateTimerDisplay('challenge');
        
        updateScore();
        updateLives();
        updateProgress();
        
        currentQuestions = shuffleArrayRandom([...currentQuestions]);
        
        if (challengeTimerInterval) clearInterval(challengeTimerInterval);
        challengeTimerInterval = setInterval(() => {
            if (!gameActive) return;
            if (currentCycle === 2) {
                challengeTimeLeft--;
                updateTimerDisplay('challenge');
                
                if (challengeTimeLeft <= 0) {
                    clearInterval(challengeTimerInterval);
                    gameActive = false;
                    answerInput.disabled = true;
                    document.getElementById('message').innerHTML = `
                        <div class="text-center">
                            <span class="text-red-600 text-xl block mb-2">⏰ TIME'S UP! ⏰</span>
                            <span class="text-gray-700 block">You completed ${currentIndex}/${currentQuestions.length} questions.</span>
                            <span class="text-gray-700 block">Your score: ${score} points</span>
                            <button onclick="resetToCycle1()" class="mt-3 bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-4 py-2 rounded-full text-sm">Try Again</button>
                        </div>
                    `;
                    document.getElementById('inputHint').innerHTML = 'Time is up! Click "Try Again" to restart.';
                    answerInput.style.opacity = '0.5';
                }
            }
        }, 1000);
        
        loadCurrentQuestion();
        answerInput.focus();
    }
    
    function updateTimerDisplay(mode) {
        if (mode === 'practice') {
            const minutes = Math.floor(practiceSeconds / 60);
            const seconds = practiceSeconds % 60;
            timerDisplaySpan.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            timerDisplaySpan.style.color = '#5A6B2D';
        } else {
            const minutes = Math.floor(challengeTimeLeft / 60);
            const seconds = challengeTimeLeft % 60;
            timerDisplaySpan.innerHTML = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            if (challengeTimeLeft <= 10) {
                timerDisplaySpan.style.color = '#E85D3A';
            } else if (challengeTimeLeft <= 30) {
                timerDisplaySpan.style.color = '#E8A317';
            } else {
                timerDisplaySpan.style.color = '#5A6B2D';
            }
        }
    }
    
    function resetToCycle1() {
        if (practiceTimerInterval) clearInterval(practiceTimerInterval);
        if (challengeTimerInterval) clearInterval(challengeTimerInterval);
        answerInput.disabled = false;
        answerInput.value = '';
        answerInput.style.opacity = '1';
        document.getElementById('message').innerHTML = '';
        startPracticeMode();
    }
    
    function loadCurrentQuestion() {
        if (!gameActive) return;
        
        if (currentIndex >= currentQuestions.length) {
            completeCycle();
            return;
        }
        
        const q = currentQuestions[currentIndex];
        let displayText = q.text;
        
        if (q.type === 'addition' || q.type === 'addition_level2' || q.type === 'addition_up_to_20' || q.type === 'teen_addition') {
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
        if (gameActive && !answerInput.disabled) {
            const userAnswer = parseInt(answerInput.value);
            if (isNaN(userAnswer)) {
                document.getElementById('message').innerHTML = '<span class="text-orange-500">Please type a number!</span>';
                return;
            }
            checkAnswer(userAnswer);
        }
    });
    
    function checkAnswer(userAnswer) {
        if (!gameActive) return;
        
        const q = currentQuestions[currentIndex];
        const isCorrect = (userAnswer === q.answer);
        
        if (isCorrect) {
            let points = (currentCycle === 2) ? 15 : 10;
            score += points;
            currentIndex++;
            document.getElementById('message').innerHTML = `<span class="text-green-600">✅ Correct! +${points} points!</span>`;
            updateScore();
            updateProgress();
            setTimeout(() => loadCurrentQuestion(), 300);
        } else {
            lives--;
            updateLives();
            document.getElementById('message').innerHTML = `<span class="text-red-600">❌ Wrong! The answer is ${q.answer}. -1 life</span>`;
            
            if (lives <= 0) {
                gameActive = false;
                answerInput.disabled = true;
                if (currentCycle === 1 && practiceTimerInterval) clearInterval(practiceTimerInterval);
                if (currentCycle === 2 && challengeTimerInterval) clearInterval(challengeTimerInterval);
                
                if (currentCycle === 1) {
                    document.getElementById('message').innerHTML = `
                        <div class="text-center">
                            <span class="text-red-600 text-xl block mb-2">💔 GAME OVER! 💔</span>
                            <span class="text-gray-700 block">You scored ${score} points.</span>
                            <span class="text-gray-700 block">Time spent: ${formatTime(practiceSeconds)}</span>
                            <button onclick="resetToCycle1()" class="mt-3 bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-4 py-2 rounded-full text-sm">Try Again</button>
                        </div>
                    `;
                } else {
                    document.getElementById('message').innerHTML = `
                        <div class="text-center">
                            <span class="text-red-600 text-xl block mb-2">💔 CHALLENGE FAILED! 💔</span>
                            <span class="text-gray-700 block">You scored ${score} points.</span>
                            <span class="text-gray-700 block">You completed ${currentIndex}/${currentQuestions.length} questions.</span>
                            <button onclick="resetToCycle1()" class="mt-3 bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-4 py-2 rounded-full text-sm">Back to Practice</button>
                        </div>
                    `;
                }
                document.getElementById('inputHint').innerHTML = 'Game over! Click a button to continue.';
                answerInput.style.opacity = '0.5';
            } else {
                setTimeout(() => {
                    document.getElementById('message').innerHTML = '';
                }, 1000);
            }
        }
    }
    
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
    
    function completeCycle() {
        if (isCompleting) return;
        isCompleting = true;
        
        if (currentCycle === 1) {
            if (practiceTimerInterval) clearInterval(practiceTimerInterval);
            
            const cycle1Accuracy = Math.round((score / (currentQuestions.length * 10)) * 100);
            const timeSpentSeconds = practiceSeconds;
            
            // Save to database
            saveGameResults(1, score, cycle1Accuracy, timeSpentSeconds, null);
            
            cycleCompleted = true;
            gameActive = false;
            answerInput.disabled = true;
            answerInput.style.opacity = '0.5';
            
            // Clear message and show completion
            document.getElementById('message').innerHTML = '';
            document.getElementById('message').innerHTML = `
                <div class="text-center">
                    <span class="text-green-600 text-2xl block mb-2">🎉 CYCLE 1 COMPLETE! 🎉</span>
                    <span class="text-gray-700 block">You scored ${score} points!</span>
                    <span class="text-gray-700 block">Time spent: ${formatTime(timeSpentSeconds)}</span>
                    <span class="text-purple-600 font-bold block my-3">✨ Now get ready for the TIMED CHALLENGE! ✨</span>
                    <button onclick="startChallengeMode()" class="mt-2 bg-[#E85D3A] hover:bg-[#D44A2A] text-white px-8 py-3 rounded-full text-lg font-bold transition-all transform hover:scale-105 shadow-lg">
                        ⚡ START CHALLENGE MODE ⚡
                    </button>
                </div>
            `;
            
        } else {
            if (challengeTimerInterval) clearInterval(challengeTimerInterval);
            
            const accuracy = Math.round((currentIndex / currentQuestions.length) * 100);
            const timeRemaining = challengeTimeLeft;
            
            // Save to database
            saveGameResults(2, score, accuracy, null, timeRemaining);
            
            // Backup to localStorage
            const results = {
                level: currentLevelNum,
                cycle2Completed: true,
                score: score,
                correctAnswers: currentIndex,
                totalQuestions: currentQuestions.length,
                accuracy: accuracy,
                timeRemaining: timeRemaining,
                timestamp: new Date().toISOString()
            };
            localStorage.setItem(`level_${currentLevelNum}_challenge`, JSON.stringify(results));
            
            document.getElementById('message').innerHTML = '';
            document.getElementById('message').innerHTML = `
                <div class="text-center">
                    <span class="text-green-600 text-2xl block mb-2">🏆 AMAZING! CYCLE 2 COMPLETE! 🏆</span>
                    <span class="text-gray-700 block">Questions Correct: ${currentIndex}/${currentQuestions.length}</span>
                    <span class="text-gray-700 block">Accuracy: ${accuracy}%</span>
                    <span class="text-gray-700 block">Time Remaining: ${timeRemaining} seconds</span>
                    <span class="text-purple-600 font-bold block my-3">Total Score: ${score} points!</span>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button onclick="resetToCycle1()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-6 py-2 rounded-full text-base font-bold transition-all hover:scale-105">Play Again</button>
                        <button onclick="window.location.href='/game/levels'" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-6 py-2 rounded-full text-base font-bold transition-all hover:scale-105">Back to Levels</button>
                    </div>
                </div>
            `;
            
            gameActive = false;
            answerInput.disabled = true;
            answerInput.style.opacity = '0.5';
        }
        
        setTimeout(() => {
            isCompleting = false;
        }, 1000);
    }
    
    function updateScore() {
        document.getElementById('score').innerHTML = score;
    }
    
    function updateLives() {
        document.getElementById('lives').innerHTML = lives;
    }
    
    function updateProgress() {
        const percent = (currentIndex / currentQuestions.length) * 100;
        document.getElementById('progressBar').style.width = `${percent}%`;
        document.getElementById('questionCounter').innerHTML = `${currentIndex}/${currentQuestions.length}`;
    }
    
    function shuffleArrayRandom(arr) {
        for (let i = arr.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [arr[i], arr[j]] = [arr[j], arr[i]];
        }
        return arr;
    }
    
    // Initialize
    document.getElementById('levelIcon').innerHTML = levelData.icon;
    document.getElementById('levelName').innerHTML = levelData.name;
    document.getElementById('target').innerHTML = levelData.targetScore;
    
    startPracticeMode();
</script>
@endsection