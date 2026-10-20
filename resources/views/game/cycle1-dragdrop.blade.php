@extends('layouts.student')

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
                        <span class="text-2xl" id="levelIcon">🔷</span>
                        <span class="text-xl font-bold text-[#5A6B2D]" id="levelName">Drag & Drop Challenge</span>
                    </div>
                </div>

                <div class="text-center mb-2">
                    <div class="inline-flex items-center space-x-2 bg-[#E8F0E0] rounded-full px-4 py-1">
                        <span class="text-sm">🎯 Drag & Drop Mode - 60 Seconds</span>
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
                    <div class="text-center bg-[#F0E8D0] rounded-2xl px-6 py-2 shadow-inner" id="timerBox">
                        <span class="text-2xl">⏱️</span>
                        <span class="text-2xl font-bold text-[#E85D3A]" id="timerDisplay">01:00</span>
                        <p class="text-xs text-[#7A8B3B] font-semibold">Time Left</p>
                    </div>
                </div>

                <div class="px-8 mb-3">
                    <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                        <span>Progress</span>
                        <span id="progressPercent"></span>
                    </div>
                    <div class="bg-[#E8D5A3] rounded-full h-2 overflow-hidden">
                        <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                </div>

                <!-- Drag & Drop Content Area -->
                <div id="dragDropContent">
                    <!-- Content will be loaded dynamically -->
                </div>

                <div class="h-2 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>
            </div>
        </div>

        <div class="text-center mt-6">
            <div
                class="inline-flex items-center space-x-3 bg-[#FDF8F0]/90 rounded-full px-5 py-2 shadow-lg border border-[#E8D5A3]">
                <span class="text-3xl" id="helperIcon">🎯</span>
                <p class="text-[#5A6B2D] font-semibold text-sm md:text-base" id="helperMessage">"Drag two numbers that make
                    10 into Box 1!"</p>
            </div>
        </div>
    </div>

    @include('game.levels.level-16-20')

    <script>
        // ============================================
        // CYCLE 1: DRAG & DROP MODE WITH TIMER (Levels 16-20)
        // Saves results ONCE at the end of the cycle attempt
        // FIXED: Allows duplicate numbers in Box 1 (for 5+5)
        // ============================================

        const levelSystem = {
            ...levels16To20
        };

        const currentLevelNum = parseInt(new URLSearchParams(window.location.search).get('level')) || 16;
        const levelData = levelSystem[currentLevelNum];

        // ============================================
        // DRAG & DROP GAME WITH TIMER
        // ============================================
        function initDragDropGame() {
            let questions = [];
            let currentQuestionIndex = 0;
            let score = 0;
            let correctCount = 0;
            let wrongCount = 0;
            let currentNumbers = [];
            let box1Numbers = [];
            let box2Numbers = [];
            let box1Valid = false;
            let gameActive = true;
            let timeLeft = 60;
            let timerInterval = null;
            let draggedItem = null;
            let draggedValue = null;
            let draggedElement = null;
            let draggedFromBox = null;
            let resultsSaved = false; // Prevent multiple saves

            const dragDropContent = document.getElementById('dragDropContent');

            // Generate questions for drag & drop
            function generateDragDropQuestions() {
                const qs = [];
                const pairs = [
                    [1, 9],
                    [2, 8],
                    [3, 7],
                    [4, 6],
                    [5, 5],
                    [6, 4],
                    [7, 3],
                    [8, 2],
                    [9, 1]
                ];

                let maxThirdNumber = 9;
                if (currentLevelNum >= 17) maxThirdNumber = 12;
                if (currentLevelNum >= 18) maxThirdNumber = 15;
                if (currentLevelNum >= 19) maxThirdNumber = 18;
                if (currentLevelNum >= 20) maxThirdNumber = 20;

                for (let i = 0; i < 10; i++) {
                    const pair = pairs[Math.floor(Math.random() * pairs.length)];
                    let thirdNumber;
                    do {
                        thirdNumber = Math.floor(Math.random() * (maxThirdNumber - 1)) + 2;
                    } while (thirdNumber === pair[0] || thirdNumber === pair[1]);

                    const numbers = [pair[0], pair[1], thirdNumber];
                    for (let j = numbers.length - 1; j > 0; j--) {
                        const k = Math.floor(Math.random() * (j + 1));
                        [numbers[j], numbers[k]] = [numbers[k], numbers[j]];
                    }

                    qs.push({
                        numbers: numbers,
                        pair: pair,
                        remaining: thirdNumber,
                        total: 10 + thirdNumber
                    });
                }
                return qs;
            }

            // Create drag & drop interface
            dragDropContent.innerHTML = `
                <div class="mx-6 mb-4">
                    <div class="bg-[#E8F0E0] rounded-2xl p-4 text-center shadow-inner border border-[#D0E0B8] mb-4">
                        <div class="text-3xl md:text-4xl font-bold mb-2 text-[#5A6B2D]" id="question">Loading...</div>
                    </div>
                    
                    <!-- Numbers Container (Original pool) -->
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mb-4">
                        <h3 class="text-center text-lg font-bold text-[#5A6B2D] mb-3">📦 Number Pool</h3>
                        <div id="numbersContainer" class="flex justify-center gap-4 flex-wrap min-h-[120px]"></div>
                    </div>

                    <!-- Drop Zones -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-[#E8F0E0] rounded-2xl p-4 border-4 border-[#D4A06A] transition-all duration-300" id="box1">
                            <h3 class="text-center text-lg font-bold text-[#5A6B2D] mb-2">📦 Box 1 (Make 10)</h3>
                            <div id="box1Numbers" class="min-h-[80px] bg-white/50 rounded-xl p-3 flex justify-center items-center gap-2 flex-wrap">
                                <span class="text-gray-400 text-sm">Drop numbers here to make 10 →</span>
                            </div>
                            <div class="text-center mt-2">
                                <span class="text-sm text-[#7A8B3B]" id="box1Sum">Sum: 0</span>
                            </div>
                        </div>

                        <div class="bg-[#E8F0E0] rounded-2xl p-4 border-4 border-[#D4A06A] transition-all duration-300" id="box2">
                            <h3 class="text-center text-lg font-bold text-[#5A6B2D] mb-2">📦 Box 2 (Remaining)</h3>
                            <div id="box2Numbers" class="min-h-[80px] bg-white/50 rounded-xl p-3 flex justify-center items-center gap-2 flex-wrap">
                                <span class="text-gray-400 text-sm">Drop remaining number here →</span>
                            </div>
                            <div class="text-center mt-2">
                                <span class="text-sm text-[#7A8B3B]" id="box2Sum">Sum: 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Answer Area -->
                    <div class="bg-[#E8F0E0] rounded-2xl p-4 text-center shadow-inner border border-[#D0E0B8]">
                        <div class="flex justify-center items-center gap-4">
                            <span class="text-3xl font-bold text-[#5A6B2D]">=</span>
                            <input type="number" id="dragAnswerInput" 
                                class="text-center text-3xl font-bold text-[#5A6B2D] bg-white border-2 border-[#E8D5A3] rounded-xl p-3 w-32"
                                placeholder="?" disabled>
                            <button id="submitDragAnswer" 
                                class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-6 py-2 rounded-full font-bold transition-all hover:scale-105 shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                                Submit ✓
                            </button>
                        </div>
                        <p class="text-sm text-[#8C9DB3] mt-2">Make Box 1 sum to 10 first, then type your answer!</p>
                    </div>
                </div>
                <div class="pb-6 px-6 text-center">
                    <div id="message" class="text-lg font-bold min-h-[70px]"></div>
                </div>
            `;

            questions = generateDragDropQuestions();

            // DOM Elements
            const numbersContainer = document.getElementById('numbersContainer');
            const box1NumbersDiv = document.getElementById('box1Numbers');
            const box2NumbersDiv = document.getElementById('box2Numbers');
            const box1Div = document.getElementById('box1');
            const box2Div = document.getElementById('box2');
            const answerInput = document.getElementById('dragAnswerInput');
            const submitBtn = document.getElementById('submitDragAnswer');

            // Timer function
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
                        showTimeUp();
                    }
                }, 1000);
            }

            // Save results ONCE at the end of the cycle attempt
            async function saveResults() {
                if (resultsSaved) return; // Prevent multiple saves
                resultsSaved = true;

                const totalAttempted = correctCount + wrongCount;

                const data = {
                    level_id: currentLevelNum,
                    questions_answered: totalAttempted,
                    time_limit: 60 - timeLeft,
                    correct_answers: correctCount,
                    wrong_answers: wrongCount
                };

                console.log('Saving Cycle 1 (Drag & Drop):', data);

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
                        <span class="text-red-600 text-3xl block mb-3">⏰ TIME'S UP! ⏰</span>
                        <div class="bg-red-50 rounded-xl p-5 mb-5">
                            <p class="text-gray-700 text-lg mb-2">You completed ${currentQuestionIndex} questions!</p>
                            <p class="text-gray-700 text-lg mb-2">⭐ Score: ${score} points</p>
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

                // Disable all draggable items
                const draggables = document.querySelectorAll('.draggable-number, .box1-number, .box2-number');
                draggables.forEach(el => {
                    el.setAttribute('draggable', 'false');
                    el.classList.add('opacity-50');
                });

                answerInput.disabled = true;
                submitBtn.disabled = true;

                // Save results ONCE at the end
                saveResults();
            }

            function makeElementDraggable(element, value, sourceType) {
                if (!gameActive) return;
                element.setAttribute('draggable', 'true');
                element.setAttribute('data-value', value);
                element.setAttribute('data-source', sourceType);
                element.addEventListener('dragstart', handleDragStart);
                element.addEventListener('dragend', handleDragEnd);
            }

            function handleDragStart(e) {
                if (!gameActive) {
                    e.preventDefault();
                    return false;
                }
                draggedItem = this;
                draggedValue = parseInt(this.dataset.value);
                draggedElement = this;
                draggedFromBox = this.dataset.source;
                e.dataTransfer.setData('text/plain', this.dataset.value);
                this.classList.add('opacity-50');
            }

            function handleDragEnd(e) {
                if (draggedItem) {
                    draggedItem.classList.remove('opacity-50');
                    draggedItem = null;
                    draggedValue = null;
                    draggedElement = null;
                    draggedFromBox = null;
                }
            }

            function setupDropZones() {
                const finalBox1Div = document.getElementById('box1');
                const finalBox2Div = document.getElementById('box2');
                const finalNumbersContainer = document.getElementById('numbersContainer');
                const finalBox1NumbersDiv = document.getElementById('box1Numbers');
                const finalBox2NumbersDiv = document.getElementById('box2Numbers');

                finalNumbersContainer.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    finalNumbersContainer.classList.add('border-blue-500', 'bg-blue-50');
                });

                finalNumbersContainer.addEventListener('dragleave', (e) => {
                    finalNumbersContainer.classList.remove('border-blue-500', 'bg-blue-50');
                });

                finalNumbersContainer.addEventListener('drop', (e) => {
                    e.preventDefault();
                    finalNumbersContainer.classList.remove('border-blue-500', 'bg-blue-50');


                    if (draggedItem && draggedValue !== null && draggedElement && gameActive) {
                        // Check if the dragged item is already from the pool
                        if (draggedFromBox === 'pool') {
                            // It's already in the pool, ignore the drop
                            document.getElementById('message').innerHTML =
                                '<span class="text-yellow-600">⏳ This number is already in the pool!</span>';
                            setTimeout(() => {
                                if (document.getElementById('message').innerHTML.includes(
                                        'already in the pool')) {
                                    document.getElementById('message').innerHTML = '';
                                }
                            }, 1500);
                            return;
                        }
                        returnToPool(draggedValue, draggedElement, draggedFromBox);
                    }
                });

                // Box 1 drop zone
                finalBox1NumbersDiv.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    finalBox1Div.classList.add('border-blue-500', 'bg-blue-50');
                });

                finalBox1NumbersDiv.addEventListener('dragleave', (e) => {
                    finalBox1Div.classList.remove('border-blue-500', 'bg-blue-50');
                });

                finalBox1NumbersDiv.addEventListener('drop', (e) => {
                    e.preventDefault();
                    finalBox1Div.classList.remove('border-blue-500', 'bg-blue-50');
                    if (draggedItem && draggedValue !== null && draggedElement && gameActive) {
                        addToBox1(draggedValue, draggedElement, draggedFromBox);
                    }
                });

                // Box 2 drop zone
                finalBox2NumbersDiv.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    finalBox2Div.classList.add('border-blue-500', 'bg-blue-50');
                });

                finalBox2NumbersDiv.addEventListener('dragleave', (e) => {
                    finalBox2Div.classList.remove('border-blue-500', 'bg-blue-50');
                });

                finalBox2NumbersDiv.addEventListener('drop', (e) => {
                    e.preventDefault();
                    finalBox2Div.classList.remove('border-blue-500', 'bg-blue-50');
                    if (draggedItem && draggedValue !== null && draggedElement && gameActive) {
                        addToBox2(draggedValue, draggedElement, draggedFromBox);
                    }
                });
            }

            function returnToPool(value, element, fromBox) {
                // For counting how many of this value are in currentNumbers (to track duplicates)
                const countInPool = currentNumbers.filter(n => n === value).length;
                const countInBox1 = box1Numbers.filter(n => n === value).length;
                const countInBox2 = box2Numbers.filter(n => n === value).length;
                const totalInBoxes = countInBox1 + countInBox2;

                // Check if this specific instance is already represented
                // We need to track by element reference, not just value, for duplicates

                if (fromBox === 'box1') {
                    // Find and remove the specific element instance from box1Numbers
                    const indexToRemove = (() => {
                        for (let i = 0; i < box1Numbers.length; i++) {
                            if (box1Numbers[i] === value) {
                                // Return the first matching index (we'll remove one instance)
                                return i;
                            }
                        }
                        return -1;
                    })();

                    if (indexToRemove !== -1) {
                        box1Numbers.splice(indexToRemove, 1);
                    }

                    const box1Sum = box1Numbers.reduce((a, b) => a + b, 0);
                    box1Valid = (box1Sum === 10);

                    const box1DivEl = document.getElementById('box1');
                    if (box1Valid) {
                        box1DivEl.classList.add('border-green-500', 'bg-green-50');
                        box1DivEl.classList.remove('border-[#D4A06A]');
                    } else {
                        box1DivEl.classList.remove('border-green-500', 'bg-green-50');
                        box1DivEl.classList.add('border-[#D4A06A]');
                    }

                    if (!box1Valid) {
                        answerInput.disabled = true;
                        submitBtn.disabled = true;
                    }
                } else if (fromBox === 'box2') {
                    const indexToRemove = (() => {
                        for (let i = 0; i < box2Numbers.length; i++) {
                            if (box2Numbers[i] === value) {
                                return i;
                            }
                        }
                        return -1;
                    })();

                    if (indexToRemove !== -1) {
                        box2Numbers.splice(indexToRemove, 1);
                    }
                }

                // Add back to pool
                currentNumbers.push(value);
                if (element && element.remove) element.remove();

                updateNumbersDisplay();
                updateBoxesDisplay();
                updateSums();

                draggedItem = null;
                draggedValue = null;
                draggedElement = null;
                draggedFromBox = null;

                document.getElementById('message').innerHTML =
                    '<span class="text-blue-600">🔄 Number returned to pool. Try again!</span>';
                setTimeout(() => {
                    if (document.getElementById('message').innerHTML.includes('returned to pool')) {
                        document.getElementById('message').innerHTML = '';
                    }
                }, 1500);
            }

            function addToBox1(value, element, fromBox) {
                // For 5+5 scenario, we need to allow duplicates in Box 1
                // We don't check if box1Numbers.includes(value) anymore

                const newSum = box1Numbers.reduce((a, b) => a + b, 0) + value;

                if (newSum === 10) {
                    box1Numbers.push(value);

                    if (fromBox === 'pool') {
                        // Remove ONE instance of this value from currentNumbers
                        const indexToRemove = (() => {
                            for (let i = 0; i < currentNumbers.length; i++) {
                                if (currentNumbers[i] === value) {
                                    return i;
                                }
                            }
                            return -1;
                        })();

                        if (indexToRemove !== -1) {
                            currentNumbers.splice(indexToRemove, 1);
                        }
                        if (element && element.remove) element.remove();
                    } else if (fromBox === 'box2') {
                        const indexToRemove = (() => {
                            for (let i = 0; i < box2Numbers.length; i++) {
                                if (box2Numbers[i] === value) {
                                    return i;
                                }
                            }
                            return -1;
                        })();

                        if (indexToRemove !== -1) {
                            box2Numbers.splice(indexToRemove, 1);
                        }
                        if (element && element.remove) element.remove();
                    }

                    updateNumbersDisplay();
                    updateBoxesDisplay();
                    updateSums();

                    box1Valid = true;
                    const box1DivEl = document.getElementById('box1');
                    box1DivEl.classList.add('border-green-500', 'bg-green-50');
                    box1DivEl.classList.remove('border-[#D4A06A]');

                    if (box2Numbers.length === 1) {
                        enableAnswerInput();
                    }

                    document.getElementById('message').innerHTML =
                        '<span class="text-green-600">✅ Perfect! You made 10 in Box 1!</span>';
                    setTimeout(() => {
                        if (document.getElementById('message').innerHTML.includes('Perfect')) {
                            document.getElementById('message').innerHTML = '';
                        }
                    }, 2000);
                } else if (newSum < 10) {
                    box1Numbers.push(value);

                    if (fromBox === 'pool') {
                        const indexToRemove = (() => {
                            for (let i = 0; i < currentNumbers.length; i++) {
                                if (currentNumbers[i] === value) {
                                    return i;
                                }
                            }
                            return -1;
                        })();

                        if (indexToRemove !== -1) {
                            currentNumbers.splice(indexToRemove, 1);
                        }
                        if (element && element.remove) element.remove();
                    } else if (fromBox === 'box2') {
                        const indexToRemove = (() => {
                            for (let i = 0; i < box2Numbers.length; i++) {
                                if (box2Numbers[i] === value) {
                                    return i;
                                }
                            }
                            return -1;
                        })();

                        if (indexToRemove !== -1) {
                            box2Numbers.splice(indexToRemove, 1);
                        }
                        if (element && element.remove) element.remove();
                    }

                    updateNumbersDisplay();
                    updateBoxesDisplay();
                    updateSums();

                    document.getElementById('message').innerHTML =
                        '<span class="text-blue-600">📦 Add more to make 10...</span>';
                    setTimeout(() => {
                        if (document.getElementById('message').innerHTML.includes('Add more')) {
                            document.getElementById('message').innerHTML = '';
                        }
                    }, 1500);
                } else {
                    const box1DivEl = document.getElementById('box1');
                    box1DivEl.classList.add('border-red-500', 'bg-red-50');
                    document.getElementById('message').innerHTML =
                        '<span class="text-red-600">❌ That would make more than 10! Try a different number!</span>';
                    setTimeout(() => {
                        box1DivEl.classList.remove('border-red-500', 'bg-red-50');
                        if (document.getElementById('message').innerHTML.includes('would make more')) {
                            document.getElementById('message').innerHTML = '';
                        }
                    }, 2000);
                }

                draggedItem = null;
                draggedValue = null;
                draggedElement = null;
                draggedFromBox = null;
            }

            function addToBox2(value, element, fromBox) {
                // Box 2 should only have one number total
                if (box2Numbers.length >= 1) {
                    document.getElementById('message').innerHTML =
                        '<span class="text-red-600">❌ Box 2 can only have one number!</span>';
                    setTimeout(() => {
                        if (document.getElementById('message').innerHTML.includes('Box 2')) {
                            document.getElementById('message').innerHTML = '';
                        }
                    }, 1500);
                    return;
                }

                box2Numbers.push(value);

                if (fromBox === 'pool') {
                    const indexToRemove = (() => {
                        for (let i = 0; i < currentNumbers.length; i++) {
                            if (currentNumbers[i] === value) {
                                return i;
                            }
                        }
                        return -1;
                    })();

                    if (indexToRemove !== -1) {
                        currentNumbers.splice(indexToRemove, 1);
                    }
                    if (element && element.remove) element.remove();
                } else if (fromBox === 'box1') {
                    const indexToRemove = (() => {
                        for (let i = 0; i < box1Numbers.length; i++) {
                            if (box1Numbers[i] === value) {
                                return i;
                            }
                        }
                        return -1;
                    })();

                    if (indexToRemove !== -1) {
                        box1Numbers.splice(indexToRemove, 1);
                    }
                    if (element && element.remove) element.remove();

                    const box1Sum = box1Numbers.reduce((a, b) => a + b, 0);
                    box1Valid = (box1Sum === 10);

                    const box1DivEl = document.getElementById('box1');
                    if (box1Valid) {
                        box1DivEl.classList.add('border-green-500', 'bg-green-50');
                        box1DivEl.classList.remove('border-[#D4A06A]');
                    } else {
                        box1DivEl.classList.remove('border-green-500', 'bg-green-50');
                        box1DivEl.classList.add('border-[#D4A06A]');
                    }

                    if (!box1Valid) {
                        answerInput.disabled = true;
                        submitBtn.disabled = true;
                    }
                }

                updateNumbersDisplay();
                updateBoxesDisplay();
                updateSums();

                if (box1Valid && box2Numbers.length === 1) {
                    enableAnswerInput();
                }

                const q = questions[currentQuestionIndex];
                if (box2Numbers[0] === q.remaining) {
                    const box2DivEl = document.getElementById('box2');
                    box2DivEl.classList.add('border-green-500', 'bg-green-50');
                    box2DivEl.classList.remove('border-[#D4A06A]');
                }

                draggedItem = null;
                draggedValue = null;
                draggedElement = null;
                draggedFromBox = null;
            }

            function enableAnswerInput() {
                if (!gameActive) return;
                answerInput.disabled = false;
                submitBtn.disabled = false;
                answerInput.focus();
                document.getElementById('message').innerHTML =
                    '<span class="text-green-600">✨ Great! Now type the total sum! ✨</span>';
                setTimeout(() => {
                    if (document.getElementById('message').innerHTML.includes('Great!')) {
                        document.getElementById('message').innerHTML = '';
                    }
                }, 2000);
            }

            function updateNumbersDisplay() {
                if (!numbersContainer) return;

                if (currentNumbers.length === 0) {
                    numbersContainer.innerHTML = '<p class="text-gray-400 text-center">All numbers placed! 🎉</p>';
                    return;
                }

                numbersContainer.innerHTML = currentNumbers.map((num, index) => `
                    <div class="draggable-number w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white text-3xl font-bold flex items-center justify-center shadow-lg cursor-move hover:scale-110 transition-all"
                        draggable="true"
                        data-value="${num}"
                        data-source="pool"
                        data-instance-index="${index}">
                        ${num}
                    </div>
                `).join('');

                const poolNumbers = document.querySelectorAll('#numbersContainer .draggable-number');
                poolNumbers.forEach(el => {
                    makeElementDraggable(el, parseInt(el.dataset.value), 'pool');
                });
            }

            function updateBoxesDisplay() {
                if (box1NumbersDiv) {
                    if (box1Numbers.length === 0) {
                        box1NumbersDiv.innerHTML =
                            '<span class="text-gray-400 text-sm">Drop numbers here to make 10 →</span>';
                    } else {
                        box1NumbersDiv.innerHTML = box1Numbers.map((num, index) => `
                            <div class="box1-number w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 text-white text-2xl font-bold flex items-center justify-center shadow-md cursor-move hover:scale-110 transition-all"
                                draggable="true"
                                data-value="${num}"
                                data-source="box1"
                                data-instance-index="${index}">
                                ${num}
                            </div>
                        `).join('');

                        const box1NumbersEl = document.querySelectorAll('#box1Numbers .box1-number');
                        box1NumbersEl.forEach(el => {
                            makeElementDraggable(el, parseInt(el.dataset.value), 'box1');
                        });
                    }
                }

                if (box2NumbersDiv) {
                    if (box2Numbers.length === 0) {
                        box2NumbersDiv.innerHTML =
                        '<span class="text-gray-400 text-sm">Drop remaining number here →</span>';
                    } else {
                        box2NumbersDiv.innerHTML = box2Numbers.map((num, index) => `
                            <div class="box2-number w-16 h-16 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-white text-2xl font-bold flex items-center justify-center shadow-md cursor-move hover:scale-110 transition-all"
                                draggable="true"
                                data-value="${num}"
                                data-source="box2"
                                data-instance-index="${index}">
                                ${num}
                            </div>
                        `).join('');

                        const box2NumbersEl = document.querySelectorAll('#box2Numbers .box2-number');
                        box2NumbersEl.forEach(el => {
                            makeElementDraggable(el, parseInt(el.dataset.value), 'box2');
                        });
                    }
                }
            }

            function updateSums() {
                const box1Sum = box1Numbers.reduce((a, b) => a + b, 0);
                const box2Sum = box2Numbers.reduce((a, b) => a + b, 0);
                const box1SumEl = document.getElementById('box1Sum');
                const box2SumEl = document.getElementById('box2Sum');
                if (box1SumEl) box1SumEl.innerHTML = `Sum: ${box1Sum}`;
                if (box2SumEl) box2SumEl.innerHTML = `Sum: ${box2Sum}`;
            }

            function updateProgress() {
                const percent = (currentQuestionIndex / questions.length) * 100;
                document.getElementById('progressBar').style.width = `${percent}%`;
                document.getElementById('progressPercent').innerHTML = `${Math.round(percent)}%`;
            }

            function loadQuestion() {
                if (!gameActive) return;

                if (currentQuestionIndex >= questions.length) {
                    completeDragGame();
                    return;
                }

                const q = questions[currentQuestionIndex];
                currentNumbers = [...q.numbers];
                box1Numbers = [];
                box2Numbers = [];
                box1Valid = false;

                if (numbersContainer) numbersContainer.innerHTML = '';
                if (box1NumbersDiv) box1NumbersDiv.innerHTML =
                    '<span class="text-gray-400 text-sm">Drop numbers here to make 10 →</span>';
                if (box2NumbersDiv) box2NumbersDiv.innerHTML =
                    '<span class="text-gray-400 text-sm">Drop remaining number here →</span>';

                updateNumbersDisplay();
                updateBoxesDisplay();
                updateSums();
                updateProgress();

                if (answerInput) {
                    answerInput.value = '';
                    answerInput.disabled = true;
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                const box1DivEl = document.getElementById('box1');
                const box2DivEl = document.getElementById('box2');
                if (box1DivEl) {
                    box1DivEl.classList.remove('border-green-500', 'bg-green-50');
                    box1DivEl.classList.add('border-[#D4A06A]');
                }
                if (box2DivEl) {
                    box2DivEl.classList.remove('border-green-500', 'bg-green-50');
                    box2DivEl.classList.add('border-[#D4A06A]');
                }

                const questionElement = document.getElementById('question');
                if (questionElement) {
                    questionElement.innerHTML = q.numbers.join(' + ') + ' = ?';
                }

                const messageElement = document.getElementById('message');
                if (messageElement) {
                    messageElement.innerHTML = '';
                }

                setTimeout(() => {
                    if (gameActive) {
                        setupDropZones();
                    }
                }, 100);
            }

            function updateDragStats() {
                document.getElementById('score').innerHTML = score;
                document.getElementById('correctCount').innerHTML = correctCount;
                document.getElementById('wrongCount').innerHTML = wrongCount;
                updateProgress();
            }

            // Submit answer handler
            submitBtn.addEventListener('click', async () => {
                if (!gameActive) return;

                const userAnswer = parseInt(answerInput.value);
                const q = questions[currentQuestionIndex];
                const correctAnswer = q.total;

                if (userAnswer === correctAnswer) {
                    score += 10;
                    correctCount++;
                    document.getElementById('message').innerHTML =
                        '<span class="text-green-600 text-2xl">✅ Correct! +10 points!</span>';
                    updateDragStats();
                    currentQuestionIndex++;
                    setTimeout(() => {
                        loadQuestion();
                    }, 1500);
                } else {
                    wrongCount++;
                    document.getElementById('message').innerHTML =
                        `<span class="text-red-600 text-2xl">❌ Wrong! ${q.numbers.join(' + ')} = ${correctAnswer}.</span>`;
                    updateDragStats();
                    setTimeout(() => {
                        currentQuestionIndex++;
                        loadQuestion();
                    }, 2000);
                }
            });

            function completeDragGame() {
                gameActive = false;
                clearInterval(timerInterval);

                const totalAttempted = correctCount + wrongCount;
                const accuracy = totalAttempted > 0 ? Math.round((correctCount / totalAttempted) * 100) : 0;

                document.getElementById('message').innerHTML = `
                    <div class="text-center">
                        <span class="text-green-600 text-2xl block mb-2">🎉 GAME COMPLETE! 🎉</span>
                        <div class="bg-green-50 rounded-xl p-5 mb-5">
                            <p class="text-gray-700 text-lg mb-2">You completed all ${questions.length} questions!</p>
                            <p class="text-gray-700 text-lg mb-2">⭐ Score: ${score} points</p>
                            <p class="text-gray-700 text-lg mb-2">✅ Correct: ${correctCount}</p>
                            <p class="text-gray-700 text-lg mb-2">❌ Wrong: ${wrongCount}</p>
                            <p class="text-gray-700 text-lg">Accuracy: ${accuracy}%</p>
                            <p class="text-gray-700 text-lg">Time Remaining: ${timeLeft} seconds</p>
                        </div>
                        <div class="flex justify-center space-x-4 mt-4">
                            <button onclick="location.reload()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-6 py-2 rounded-full text-sm">Play Again</button>
                            <button onclick="window.location.href='/game/levels'" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-6 py-2 rounded-full text-sm">Back to Levels</button>
                        </div>
                    </div>
                `;

                // Disable all draggable items
                const draggables = document.querySelectorAll('.draggable-number, .box1-number, .box2-number');
                draggables.forEach(el => {
                    el.setAttribute('draggable', 'false');
                });

                answerInput.disabled = true;
                submitBtn.disabled = true;

                // Save results ONCE at the end
                saveResults();
            }

            // Update UI
            document.getElementById('levelIcon').innerHTML = levelData.icon;
            document.getElementById('levelName').innerHTML = levelData.name;
            document.getElementById('helperMessage').innerHTML =
                '"Drag two numbers that make 10 into Box 1! You have 60 seconds!"';
            document.getElementById('helperIcon').innerHTML = '🎯';

            // Start the game
            loadQuestion();
            startTimer();
        }

        // Initialize the drag & drop game
        initDragDropGame();
    </script>
@endsection
