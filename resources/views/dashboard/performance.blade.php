@extends('layouts.app')

@section('background')
<div class="page-background">
    <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
        <source src="{{ asset('videos/nature-math-bg2.mp4') }}" type="video/mp4">
        <div class="absolute inset-0 bg-gradient-to-b from-[#87CEEB] via-[#98FB98] to-[#F4A623]"></div>
    </video>
    <div class="absolute inset-0 bg-black/10"></div>
</div>
@endsection

@section('content')
<div class="relative z-10 min-h-screen py-8 px-4">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-5xl md:text-6xl font-bold mb-2" style="color: #EDEDED; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">
            📊 Performance Dashboard 📊
        </h1>
        <p class="text-xl md:text-2xl" style="color: #F4A623;">Track your math progress!</p>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto">
        
        <!-- Stats Grid - Top Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Current Level Card -->
            <div class="bg-[#8B6914]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] text-center hover:scale-105 transition-transform duration-300">
                <div class="text-5xl mb-3">🎯</div>
                <p class="text-amber-200 text-sm uppercase tracking-wide">Current Level</p>
                <p class="text-5xl font-bold text-white mt-2" id="currentLevel">1</p>
            </div>

            <!-- Most Questions Answered Card -->
            <div class="bg-[#8B6914]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] text-center hover:scale-105 transition-transform duration-300">
                <div class="text-5xl mb-3">📝</div>
                <p class="text-amber-200 text-sm uppercase tracking-wide">Most Questions Answered</p>
                <p class="text-3xl font-bold text-green-300 mt-2" id="mostQuestions">0</p>
                <p class="text-amber-300 text-sm mt-1" id="mostQuestionsLevel">Level --</p>
            </div>

            <!-- Least Questions Answered Card -->
            <div class="bg-[#8B6914]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] text-center hover:scale-105 transition-transform duration-300">
                <div class="text-5xl mb-3">📉</div>
                <p class="text-amber-200 text-sm uppercase tracking-wide">Least Questions Answered</p>
                <p class="text-3xl font-bold text-orange-300 mt-2" id="leastQuestions">0</p>
                <p class="text-amber-300 text-sm mt-1" id="leastQuestionsLevel">Level --</p>
            </div>

            <!-- Fastest Time Card -->
            <div class="bg-[#8B6914]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] text-center hover:scale-105 transition-transform duration-300">
                <div class="text-5xl mb-3">⚡</div>
                <p class="text-amber-200 text-sm uppercase tracking-wide">Fastest Time</p>
                <p class="text-3xl font-bold text-white mt-2" id="fastestTime">--:--</p>
                <p class="text-amber-300 text-sm mt-1" id="fastestTimeLevel">Level --</p>
            </div>
        </div>

        <!-- Second Row - 2 Progress Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <!-- Lesson Completion Card -->
            <div class="bg-[#8B6914]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] text-center hover:scale-105 transition-transform duration-300">
                <div class="text-5xl mb-3">📚</div>
                <p class="text-amber-200 text-sm uppercase tracking-wide">Lesson Completion</p>
                <div class="relative w-48 h-48 mx-auto mt-4">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="96" cy="96" r="88" fill="none" stroke="#4A3A28" stroke-width="12"/>
                        <circle cx="96" cy="96" r="88" fill="none" stroke="#F4A623" stroke-width="12" 
                                stroke-dasharray="553" stroke-dashoffset="553" id="lessonProgressCircle"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-5xl font-bold text-white" id="lessonPercent">0%</span>
                    </div>
                </div>
                <p class="text-amber-300 text-sm mt-4" id="lessonText">Loading...</p>
            </div>

            <!-- Level Completion Card -->
            <div class="bg-[#8B6914]/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A] text-center hover:scale-105 transition-transform duration-300">
                <div class="text-5xl mb-3">🏆</div>
                <p class="text-amber-200 text-sm uppercase tracking-wide">Level Completion</p>
                <div class="relative w-48 h-48 mx-auto mt-4">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="96" cy="96" r="88" fill="none" stroke="#4A3A28" stroke-width="12"/>
                        <circle cx="96" cy="96" r="88" fill="none" stroke="#F4A623" stroke-width="12" 
                                stroke-dasharray="553" stroke-dashoffset="553" id="levelProgressCircle"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-5xl font-bold text-white" id="levelPercent">0%</span>
                    </div>
                </div>
                <p class="text-amber-300 text-sm mt-4" id="levelText">Loading...</p>
            </div>
        </div>

        <!-- Third Row - Detailed Stats Table -->
        <div class="bg-[#5C3A1E]/80 backdrop-blur-sm rounded-2xl p-6 shadow-xl border-2 border-[#D4A06A]">
            <h2 class="text-2xl font-bold text-white text-center mb-6">📋 Level Details</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b-2 border-[#D4A06A]">
                            <th class="py-3 px-4 text-amber-200 font-bold text-center">Level</th>
                            <th class="py-3 px-4 text-amber-200 font-bold text-center" colspan="3">Cycle 1 (Practice)</th>
                            <th class="py-3 px-4 text-amber-200 font-bold text-center" colspan="3">Cycle 2 (Challenge)</th>
                            <th class="py-3 px-4 text-amber-200 font-bold text-center">Status</th>
                        </tr>
                        <tr class="border-b border-[#D4A06A]/50">
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">#</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Attempts</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Total Questions</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Correct</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Attempts</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Accuracy</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Time Spent</th>
                            <th class="py-2 px-4 text-amber-200/80 text-sm text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="levelStatsBody">
                        <!-- JavaScript will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Jungle Decoration -->
    <div class="flex justify-center space-x-8 mt-10">
        <span class="text-4xl">🌴</span>
        <span class="text-4xl">🍃</span>
        <span class="text-4xl">🌿</span>
        <span class="text-4xl">🦜</span>
    </div>
</div>

<script>
    // ============================================
    // PERFORMANCE DASHBOARD - FETCHING FROM DATABASE
    // ============================================

    let cycle1Data = [];
    let cycle2Data = [];
    let totalLevels = 0;
    let totalLessons = 0;

    // Format time as MM:SS
    function formatTime(seconds) {
        if (!seconds && seconds !== 0) return '--:--';
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Calculate time spent (60 - time_remaining)
    function calculateTimeSpent(timeRemaining) {
        if (!timeRemaining && timeRemaining !== 0) return null;
        return 60 - timeRemaining;
    }

    // Fetch total levels from database
    async function fetchTotalLevels() {
        try {
            const response = await fetch('/api/total-levels');
            if (response.ok) {
                const data = await response.json();
                totalLevels = data.total;
                console.log('Total levels:', totalLevels);
            }
        } catch (error) {
            console.error('Error fetching total levels:', error);
            totalLevels = 25; // fallback
        }
    }

    // Fetch total lessons from database
    async function fetchTotalLessons() {
        try {
            const response = await fetch('/api/total-lessons');
            if (response.ok) {
                const data = await response.json();
                totalLessons = data.total;
                console.log('Total lessons:', totalLessons);
            }
        } catch (error) {
            console.error('Error fetching total lessons:', error);
            totalLessons = 11; // fallback
        }
    }

    // Load data from database
    async function loadPerformanceData() {
        try {
            // Fetch totals first
            await fetchTotalLevels();
            await fetchTotalLessons();
            
            // Fetch Cycle 1 results
            const cycle1Response = await fetch('/api/cycle1/results');
            if (cycle1Response.ok) {
                cycle1Data = await cycle1Response.json();
                console.log('Cycle 1 data loaded:', cycle1Data.length, 'records');
            }
            
            // Fetch Cycle 2 results
            const cycle2Response = await fetch('/api/cycle2/results');
            if (cycle2Response.ok) {
                cycle2Data = await cycle2Response.json();
                console.log('Cycle 2 data loaded:', cycle2Data.length, 'records');
            }
            
            // Process and display data
            processStats();
            
        } catch (error) {
            console.error('Error loading performance data:', error);
        }
    }

    // Get best attempt for a level from cycle 1 (most questions answered)
    function getBestCycle1Attempt(levelNum) {
        const attempts = cycle1Data.filter(a => a.level_number === levelNum);
        if (attempts.length === 0) return null;
        
        return attempts.reduce((best, current) => {
            return (current.questions_answered > best.questions_answered) ? current : best;
        }, attempts[0]);
    }

    // Get best attempt for a level from cycle 2 (highest accuracy, only completed)
    function getBestCycle2Attempt(levelNum) {
        const attempts = cycle2Data.filter(a => a.level_number === levelNum && a.is_completed === 1);
        if (attempts.length === 0) return null;
        
        return attempts.reduce((best, current) => {
            return (current.accuracy > best.accuracy) ? current : best;
        }, attempts[0]);
    }

    // Get fastest time for a level from cycle 2 (lowest time spent, only from completed attempts)
    function getFastestTime(levelNum) {
        const attempts = cycle2Data.filter(a => a.level_number === levelNum && a.is_completed === 1);
        if (attempts.length === 0) return null;
        
        let fastest = null;
        let fastestTimeSpent = Infinity;
        
        for (const attempt of attempts) {
            const timeSpent = calculateTimeSpent(attempt.time_remaining);
            if (timeSpent !== null && timeSpent < fastestTimeSpent) {
                fastestTimeSpent = timeSpent;
                fastest = attempt;
            }
        }
        
        return fastest ? { ...fastest, time_spent: fastestTimeSpent } : null;
    }

    // Check if a level is completed (has both Cycle 1 and at least one completed Cycle 2 attempt)
    function isLevelCompleted(levelNum) {
        const hasCycle1 = cycle1Data.some(a => a.level_number === levelNum);
        const hasCompletedCycle2 = cycle2Data.some(a => a.level_number === levelNum && a.is_completed === 1);
        return hasCycle1 && hasCompletedCycle2;
    }

    // Load lesson progress from database
    async function loadLessonProgress() {
        try {
            const response = await fetch('/api/lesson-progress');
            if (response.ok) {
                const data = await response.json();
                const completedLessons = data.completed_lessons || [];
                const lessonCount = completedLessons.length;
                const lessonPercent = totalLessons > 0 ? Math.round((lessonCount / totalLessons) * 100) : 0;
                
                document.getElementById('lessonPercent').innerHTML = `${lessonPercent}%`;
                document.getElementById('lessonText').innerHTML = `${lessonCount} of ${totalLessons} lessons completed`;
                
                const lessonCircumference = 553;
                const lessonOffset = lessonCircumference - (lessonPercent / 100) * lessonCircumference;
                const lessonCircle = document.getElementById('lessonProgressCircle');
                if (lessonCircle) {
                    lessonCircle.style.strokeDashoffset = lessonOffset;
                }
            }
        } catch (error) {
            console.error('Error loading lesson progress:', error);
        }
    }

    // Calculate all statistics
    function processStats() {
        let mostQuestions = { count: 0, level: null };
        let leastQuestions = { count: Infinity, level: null };
        let fastestTime = { time: Infinity, level: null };
        let completedCount = 0;
        
        // Process each level
        for (let i = 1; i <= totalLevels; i++) {
            const bestCycle1 = getBestCycle1Attempt(i);
            const fastestTimeAttempt = getFastestTime(i);
            
            // Update most questions answered
            if (bestCycle1 && bestCycle1.questions_answered > mostQuestions.count) {
                mostQuestions.count = bestCycle1.questions_answered;
                mostQuestions.level = i;
            }
            
            // Update least questions answered
            if (bestCycle1 && bestCycle1.questions_answered < leastQuestions.count) {
                leastQuestions.count = bestCycle1.questions_answered;
                leastQuestions.level = i;
            }
            
            // Update fastest time (lowest time spent)
            if (fastestTimeAttempt && fastestTimeAttempt.time_spent < fastestTime.time) {
                fastestTime.time = fastestTimeAttempt.time_spent;
                fastestTime.level = i;
            }
            
            // Count completed levels
            if (isLevelCompleted(i)) {
                completedCount++;
            }
        }
        
        // Update UI with stats
        const nextLevel = completedCount + 1;
        document.getElementById('currentLevel').innerHTML = nextLevel <= totalLevels ? nextLevel : totalLevels;
        
        if (mostQuestions.level) {
            document.getElementById('mostQuestions').innerHTML = `${mostQuestions.count}`;
            document.getElementById('mostQuestionsLevel').innerHTML = `Level ${mostQuestions.level}`;
        } else {
            document.getElementById('mostQuestions').innerHTML = '0';
            document.getElementById('mostQuestionsLevel').innerHTML = 'No data';
        }
        
        if (leastQuestions.level && leastQuestions.count !== Infinity) {
            document.getElementById('leastQuestions').innerHTML = `${leastQuestions.count}`;
            document.getElementById('leastQuestionsLevel').innerHTML = `Level ${leastQuestions.level}`;
        } else {
            document.getElementById('leastQuestions').innerHTML = '0';
            document.getElementById('leastQuestionsLevel').innerHTML = 'No data';
        }
        
        if (fastestTime.level) {
            document.getElementById('fastestTime').innerHTML = formatTime(fastestTime.time);
            document.getElementById('fastestTimeLevel').innerHTML = `Level ${fastestTime.level}`;
        } else {
            document.getElementById('fastestTime').innerHTML = '--:--';
            document.getElementById('fastestTimeLevel').innerHTML = 'No data';
        }
        
        // Update level completion
        const levelPercent = totalLevels > 0 ? Math.round((completedCount / totalLevels) * 100) : 0;
        document.getElementById('levelPercent').innerHTML = `${levelPercent}%`;
        document.getElementById('levelText').innerHTML = `${completedCount} of ${totalLevels} levels completed`;
        
        // Update level progress circle
        const levelCircumference = 553;
        const levelOffset = levelCircumference - (levelPercent / 100) * levelCircumference;
        const levelCircle = document.getElementById('levelProgressCircle');
        if (levelCircle) {
            levelCircle.style.strokeDashoffset = levelOffset;
        }
        
        // Update level details table
        updateLevelTable();
        
        // Load lesson progress
        loadLessonProgress();
    }

    // Update the level details table
    function updateLevelTable() {
        const tbody = document.getElementById('levelStatsBody');
        tbody.innerHTML = '';
        
        for (let i = 1; i <= totalLevels; i++) {
            const bestCycle1 = getBestCycle1Attempt(i);
            const bestCycle2 = getBestCycle2Attempt(i);
            const fastestTimeAttempt = getFastestTime(i);
            const isCompleted = isLevelCompleted(i);
            
            const row = document.createElement('tr');
            row.className = 'border-b border-[#D4A06A]/30 hover:bg-[#D4A06A]/10 transition-colors';
            
            // Cycle 1 data
            const cycle1Attempts = cycle1Data.filter(a => a.level_number === i).length || 0;
            const cycle1Correct = bestCycle1 ? bestCycle1.correct_answers : 0;
            const cycle1Total = bestCycle1 ? bestCycle1.questions_answered : 0;
            const cycle1Display = cycle1Total > 0 ? `${cycle1Correct}/${cycle1Total}` : '-';
            
            // Cycle 2 data
            const cycle2AttemptsTotal = cycle2Data.filter(a => a.level_number === i).length || 0;
            const cycle2Accuracy = bestCycle2 ? `${bestCycle2.accuracy}%` : '-';
            const timeSpentDisplay = fastestTimeAttempt ? formatTime(fastestTimeAttempt.time_spent) : '-';
            
            // Status
            let statusText = '';
            let statusColor = '';
            if (isCompleted) {
                statusText = '✅ Completed';
                statusColor = 'text-green-300';
            } else if (cycle1Attempts > 0 || cycle2AttemptsTotal > 0) {
                statusText = '📖 Not Completed';
                statusColor = 'text-yellow-300';
            } else {
                statusText = '🔒 Locked';
                statusColor = 'text-gray-400';
            }
            
            row.innerHTML = `
                <td class="py-3 px-4 text-white font-bold text-center">${i}</td>
                <td class="py-3 px-4 text-white text-center">${cycle1Attempts}</td>
                <td class="py-3 px-4 text-white text-center">${cycle1Total}</td>
                <td class="py-3 px-4 text-green-300 text-center">${cycle1Display}</td>
                <td class="py-3 px-4 text-white text-center">${cycle2AttemptsTotal}</td>
                <td class="py-3 px-4 text-yellow-300 text-center">${cycle2Accuracy}</td>
                <td class="py-3 px-4 text-blue-300 text-center">${timeSpentDisplay}</td>
                <td class="py-3 px-4 ${statusColor} text-center font-semibold">${statusText}</td>
            `;
            
            tbody.appendChild(row);
        }
    }

    // Initialize the dashboard
    document.addEventListener('DOMContentLoaded', function() {
        loadPerformanceData();
    });
</script>

<style>
    /* Progress circle animation */
    #lessonProgressCircle, #levelProgressCircle {
        transition: stroke-dashoffset 1s ease-in-out;
    }
</style>
@endsection