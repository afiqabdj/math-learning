@extends('layouts.app')

@section('background')
    <div class="page-background">
        <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
            <source src="{{ asset('videos/nature-math-bg.mp4') }}" type="video/mp4">
            <div class="absolute inset-0 bg-gradient-to-b from-[#87CEEB] via-[#98FB98] to-[#F4A623]"></div>
        </video>
        <div class="absolute inset-0 bg-black/10"></div>
    </div>
@endsection

@section('content')
    <div class="relative z-10 min-h-screen py-8 px-4">

        <!-- Back Button -->
        <div class="max-w-4xl mx-auto mb-6">
            <a href="/game/levels"
                class="inline-flex items-center space-x-2 bg-white/90 backdrop-blur-sm hover:bg-white px-4 py-2 rounded-full transition-all shadow-md">
                <span>←</span>
                <span>Back to Levels</span>
            </a>
        </div>

        <!-- Level Header -->
        <div class="max-w-4xl mx-auto mb-8 text-center">
            <div
                class="inline-flex items-center space-x-3 bg-[#8B6914]/80 backdrop-blur-sm rounded-full px-6 py-3 shadow-xl border border-[#D4A06A]">
                <span class="text-4xl" id="levelIcon">🌱</span>
                <span class="text-3xl font-bold text-white" id="levelName">Level 1</span>
            </div>
        </div>

        <!-- Two Cycles Container -->
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- CYCLE 1: Practice Mode -->
            <div class="bg-[#5C3A1E]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] transition-all hover:scale-105 duration-300"
                id="cycle1Card">
                <div class="text-center">
                    <div class="text-6xl mb-3">🔄</div>
                    <h2 class="text-3xl font-bold text-yellow-300 mb-2">Cycle 1</h2>
                    <p class="text-white/80 mb-4">Practice Mode (30 seconds)</p>

                    <!-- Status Badge -->
                    <div class="inline-block px-4 py-1 rounded-full text-sm font-bold mb-4" id="cycle1StatusBadge">
                        <span id="cycle1Status">Loading...</span>
                    </div>

                    <!-- Stats -->
                    <div class="bg-black/30 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-white/70">Questions Answered:</span>
                            <span class="text-xl font-bold text-green-300" id="cycle1QuestionsAnswered">0</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-white/70">Correct:</span>
                            <span class="text-xl font-bold text-white" id="cycle1CorrectWrong">0/0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Attempts:</span>
                            <span class="text-xl font-bold text-white" id="cycle1Attempts">0</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button onclick="startCycle(1)" id="cycle1Btn"
                        class="w-full py-3 rounded-xl font-bold text-lg transition-all hover:scale-105 shadow-lg"
                        style="background: linear-gradient(135deg, #7A8B3B, #556B2F); border: 2px solid #E6D07A;">
                        Start Cycle 1 🚀
                    </button>
                </div>
            </div>

            <!-- CYCLE 2: Timed Challenge -->
            <div class="bg-[#5C3A1E]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] transition-all hover:scale-105 duration-300 opacity-60"
                id="cycle2Card">
                <div class="text-center">
                    <div class="text-6xl mb-3">⚡</div>
                    <h2 class="text-3xl font-bold text-yellow-300 mb-2">Cycle 2</h2>
                    <p class="text-white/80 mb-4">Timed Challenge Mode (60 seconds)</p>

                    <!-- Status Badge -->
                    <div class="inline-block px-4 py-1 rounded-full text-sm font-bold mb-4" id="cycle2StatusBadge">
                        <span id="cycle2Status">Loading...</span>
                    </div>

                    <!-- Stats -->
                    <div class="bg-black/30 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-white/70">Best Time Taken:</span>
                            <span class="text-xl font-bold text-yellow-300" id="cycle2BestTime">--</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-white/70">Best Accuracy:</span>
                            <span class="text-xl font-bold text-green-300" id="cycle2BestAccuracy">0%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70">Attempts:</span>
                            <span class="text-xl font-bold text-white" id="cycle2Attempts">0</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button onclick="startCycle(2)" id="cycle2Btn"
                        class="w-full py-3 rounded-xl font-bold text-lg transition-all hover:scale-105 shadow-lg opacity-50 cursor-not-allowed"
                        style="background: linear-gradient(135deg, #8C9DB3, #7D8FA8); border: 2px solid #F4A623;" disabled>
                        🔒 Complete Cycle 1 First
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // LEVEL DETAILS PAGE - FETCHING FROM DATABASE
        // ============================================

        const currentLevelNum = parseInt(new URLSearchParams(window.location.search).get('level')) || 1;
        let cycle1Data = null;
        let cycle2Data = null;
        let allCycle1Results = [];
        let allCycle2Results = [];

        // Format time (seconds to MM:SS format)
        function formatTime(seconds) {
            if (!seconds && seconds !== 0) return '--';
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // Calculate time taken from time_remaining (total time is 60 seconds for Cycle 2)
        function calculateTimeTaken(timeRemaining) {
            if (!timeRemaining && timeRemaining !== 0) return null;
            return 60 - timeRemaining;
        }

        // Load Cycle 1 data from database
        async function loadCycle1Data() {
            try {
                const response = await fetch('/api/cycle1/results');
                if (response.ok) {
                    const results = await response.json();
                    // Filter results for current level
                    const levelResults = results.filter(r => r.level_number === currentLevelNum);
                    allCycle1Results = levelResults;
                    
                    if (levelResults.length > 0) {
                        // Calculate best performance (most questions answered)
                        let bestResult = levelResults.reduce((best, current) => {
                            return (current.questions_answered > best.questions_answered) ? current : best;
                        }, levelResults[0]);
                        
                        cycle1Data = {
                            completed: true,
                            bestQuestionsAnswered: bestResult.questions_answered,
                            bestCorrect: bestResult.correct_answers,
                            bestWrong: bestResult.wrong_answers,
                            attempts: levelResults.length
                        };
                        
                        document.getElementById('cycle1QuestionsAnswered').innerHTML = bestResult.questions_answered;
                        document.getElementById('cycle1CorrectWrong').innerHTML = `${bestResult.correct_answers}/${bestResult.questions_answered}`;
                        document.getElementById('cycle1Attempts').innerHTML = levelResults.length;
                        document.getElementById('cycle1StatusBadge').className = 'inline-block px-4 py-1 rounded-full text-sm font-bold mb-4 bg-green-500/80';
                        document.getElementById('cycle1Status').innerHTML = '✅ Completed';
                        document.getElementById('cycle1Btn').innerHTML = '🔄 Retake Cycle 1';
                    } else {
                        // No results yet
                        cycle1Data = {
                            completed: false,
                            bestQuestionsAnswered: 0,
                            bestCorrect: 0,
                            bestWrong: 0,
                            attempts: 0
                        };
                        document.getElementById('cycle1QuestionsAnswered').innerHTML = '0';
                        document.getElementById('cycle1CorrectWrong').innerHTML = '0/0';
                        document.getElementById('cycle1Attempts').innerHTML = '0';
                        document.getElementById('cycle1StatusBadge').className = 'inline-block px-4 py-1 rounded-full text-sm font-bold mb-4 bg-gray-500/80';
                        document.getElementById('cycle1Status').innerHTML = 'Not Started';
                        document.getElementById('cycle1Btn').innerHTML = 'Start Cycle 1 🚀';
                    }
                    
                    return true;
                }
            } catch (error) {
                console.error('Error loading Cycle 1 data:', error);
            }
            return false;
        }

        // Load Cycle 2 data from database
        async function loadCycle2Data() {
            try {
                const response = await fetch('/api/cycle2/results');
                if (response.ok) {
                    const results = await response.json();
                    // Filter results for current level
                    const levelResults = results.filter(r => r.level_number === currentLevelNum);
                    allCycle2Results = levelResults;
                    
                    if (levelResults.length > 0) {
                        // Find best time taken (lowest time is fastest/best)
                        // Find best accuracy
                        let bestTimeTaken = Infinity;
                        let bestAccuracy = 0;
                        let bestCompleted = false;
                        
                        levelResults.forEach(result => {
                            // Calculate time taken from time_remaining
                            if (result.time_remaining !== undefined) {
                                const timeTaken = calculateTimeTaken(result.time_remaining);
                                if (timeTaken !== null && timeTaken < bestTimeTaken) {
                                    bestTimeTaken = timeTaken;
                                }
                            }
                            // Track best accuracy
                            if (result.accuracy > bestAccuracy) {
                                bestAccuracy = result.accuracy;
                            }
                            // Track if any attempt was completed
                            if (result.is_completed) {
                                bestCompleted = true;
                            }
                        });
                        
                        // If no valid time found, set to 0
                        if (bestTimeTaken === Infinity) {
                            bestTimeTaken = 0;
                        }
                        
                        cycle2Data = {
                            completed: bestCompleted,
                            bestTimeTaken: bestTimeTaken,
                            bestAccuracy: bestAccuracy,
                            attempts: levelResults.length
                        };
                        
                        document.getElementById('cycle2BestTime').innerHTML = formatTime(bestTimeTaken);
                        document.getElementById('cycle2BestAccuracy').innerHTML = bestAccuracy + '%';
                        document.getElementById('cycle2Attempts').innerHTML = levelResults.length;
                        
                        if (bestCompleted) {
                            document.getElementById('cycle2StatusBadge').className = 'inline-block px-4 py-1 rounded-full text-sm font-bold mb-4 bg-green-500/80';
                            document.getElementById('cycle2Status').innerHTML = '✅ Completed';
                            document.getElementById('cycle2Btn').innerHTML = '🔄 Retake Cycle 2';
                        } else if (levelResults.length > 0) {
                            document.getElementById('cycle2StatusBadge').className = 'inline-block px-4 py-1 rounded-full text-sm font-bold mb-4 bg-yellow-500/80';
                            document.getElementById('cycle2Status').innerHTML = '⚠️ Not Completed';
                            document.getElementById('cycle2Btn').innerHTML = 'Continue Cycle 2 ⚡';
                        } else {
                            document.getElementById('cycle2StatusBadge').className = 'inline-block px-4 py-1 rounded-full text-sm font-bold mb-4 bg-blue-500/80';
                            document.getElementById('cycle2Status').innerHTML = '🔓 Unlocked';
                            document.getElementById('cycle2Btn').innerHTML = 'Start Cycle 2 ⚡';
                        }
                    } else {
                        cycle2Data = {
                            completed: false,
                            bestTimeTaken: 0,
                            bestAccuracy: 0,
                            attempts: 0
                        };
                        
                        document.getElementById('cycle2BestTime').innerHTML = '--';
                        document.getElementById('cycle2BestAccuracy').innerHTML = '0%';
                        document.getElementById('cycle2Attempts').innerHTML = '0';
                        document.getElementById('cycle2StatusBadge').className = 'inline-block px-4 py-1 rounded-full text-sm font-bold mb-4 bg-blue-500/80';
                        document.getElementById('cycle2Status').innerHTML = '🔓 Unlocked';
                        document.getElementById('cycle2Btn').innerHTML = 'Start Cycle 2 ⚡';
                    }
                    
                    return true;
                }
            } catch (error) {
                console.error('Error loading Cycle 2 data:', error);
            }
            return false;
        }

        // Check if Cycle 1 is completed (has at least one result)
        function isCycle1Completed() {
            return allCycle1Results.length > 0;
        }

        // Update Cycle 2 UI based on Cycle 1 completion
        function updateCycle2Accessibility() {
            const cycle1Completed = isCycle1Completed();
            const cycle2Card = document.getElementById('cycle2Card');
            const cycle2Btn = document.getElementById('cycle2Btn');
            
            if (cycle1Completed) {
                // Unlock Cycle 2
                cycle2Card.style.opacity = '1';
                cycle2Btn.disabled = false;
                cycle2Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                cycle2Btn.style.background = 'linear-gradient(135deg, #F4A623, #E85D3A)';
                
                // If Cycle 2 already has data, update button text
                if (allCycle2Results.length > 0) {
                    const hasCompleted = allCycle2Results.some(r => r.is_completed);
                    if (hasCompleted) {
                        cycle2Btn.innerHTML = '🔄 Retake Cycle 2';
                    } else {
                        cycle2Btn.innerHTML = 'Continue Cycle 2 ⚡';
                    }
                } else {
                    cycle2Btn.innerHTML = 'Start Cycle 2 ⚡';
                }
            } else {
                // Cycle 2 stays locked
                cycle2Card.style.opacity = '0.6';
                cycle2Btn.disabled = true;
                cycle2Btn.classList.add('opacity-50', 'cursor-not-allowed');
                cycle2Btn.style.background = 'linear-gradient(135deg, #8C9DB3, #7D8FA8)';
                cycle2Btn.innerHTML = '🔒 Complete Cycle 1 First';
            }
        }

        // Start a cycle
        function startCycle(cycle) {
            if (cycle === 1) {
                window.location.href = `/game/cycle1?level=${currentLevelNum}`;
            } else {
                const cycle1Completed = isCycle1Completed();
                if (cycle1Completed) {
                    window.location.href = `/game/cycle2?level=${currentLevelNum}`;
                }
            }
        }

        // Set level name and icon
        function setLevelInfo() {
            const levelNames = {
                1: { name: "Making 10", icon: "🌱" },
                2: { name: "Addition Practice", icon: "🌿" },
                3: { name: "Making 10 & More", icon: "🍃" },
                4: { name: "Adding Tens", icon: "⭐" },
                5: { name: "Adding Up to 20", icon: "🏆" },
                6: { name: "Number Bonds", icon: "🔷" },
                7: { name: "Addition Strategies", icon: "🔶" },
                8: { name: "Mental Math", icon: "🟢" },
                9: { name: "Quick Addition", icon: "🟡" },
                10: { name: "Master Addition", icon: "🔴" },
                11: { name: "Three Numbers", icon: "⭐" },
                12: { name: "Three Numbers", icon: "⭐" },
                13: { name: "Three Numbers", icon: "⭐" },
                14: { name: "Three Numbers", icon: "⭐" },
                15: { name: "Three Numbers", icon: "⭐" },
                16: { name: "Make 10 Strategy", icon: "🔷" },
                17: { name: "Make 10 Strategy", icon: "🔶" },
                18: { name: "Make 10 Strategy", icon: "🟢" },
                19: { name: "Make 10 Strategy", icon: "🟡" },
                20: { name: "Make 10 Strategy", icon: "🔴" },
                21: { name: "Expert Level", icon: "🏆" },
                22: { name: "Expert Level", icon: "🏆" },
                23: { name: "Expert Level", icon: "🏆" },
                24: { name: "Expert Level", icon: "🏆" },
                25: { name: "Expert Level", icon: "🏆" }
            };
            
            const levelInfo = levelNames[currentLevelNum] || { name: `Level ${currentLevelNum}`, icon: "🎯" };
            document.getElementById('levelIcon').innerHTML = levelInfo.icon;
            document.getElementById('levelName').innerHTML = `Level ${currentLevelNum}: ${levelInfo.name}`;
        }

        // Initialize page
        async function init() {
            setLevelInfo();
            await loadCycle1Data();
            await loadCycle2Data();
            updateCycle2Accessibility();
        }
        
        init();
    </script>
@endsection