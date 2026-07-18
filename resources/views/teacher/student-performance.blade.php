@extends('layouts.teachers')

@section('content')
<div class="relative z-10 min-h-screen py-8 px-4">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                    <span>📊</span>
                    <span>Student Performance</span>
                </h1>
                <p class="text-white/70 text-sm mt-1" id="studentName">Loading student data...</p>
            </div>
            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <button onclick="window.location.href='{{ route('teacher.students') }}'" 
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm transition-all flex items-center gap-2">
                    <span>⬅️</span> Back to Students
                </button>
                <button onclick="location.reload()" 
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm transition-all flex items-center gap-2">
                    <span>🔄</span> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Current Level</p>
                    <p class="text-3xl font-bold text-[#5C3A1E] mt-1" id="currentLevel">-</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-3xl">🎯</div>
            </div>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Completed Levels</p>
                    <p class="text-3xl font-bold text-[#F4A623] mt-1" id="completedLevels">-</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-3xl">🏆</div>
            </div>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl" hidden>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Total Stars</p>
                    <p class="text-3xl font-bold text-[#5C3A1E] mt-1" id="totalStars">-</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-3xl">⭐</div>
            </div>
        </div>

        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-5 shadow-xl"  hidden>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-wider">Average Accuracy</p>
                    <p class="text-3xl font-bold text-green-600 mt-1" id="avgAccuracy">-</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-3xl">🎯</div>
            </div>
        </div>
    </div>

    <!-- Progress Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Level Completion -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
            <h3 class="text-[#5C3A1E] font-bold text-lg mb-4">📊 Level Completion</h3>
            <div class="h-64 w-full">
                <canvas id="completionChart"></canvas>
            </div>
        </div>

        <!-- Time per Level -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
            <h3 class="text-[#5C3A1E] font-bold text-lg mb-4">⏱️ Average Time per Level</h3>
            <div class="h-64 w-full">
                <canvas id="timeChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Stats Table -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
        <h3 class="text-[#5C3A1E] font-bold text-lg mb-4">📋 Detailed Level Progress</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b-2 border-[#D4A06A]">
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Level</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center" colspan="3">Cycle 1 (Practice)</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center" colspan="3">Cycle 2 (Challenge)</th>
                        <th class="py-3 px-4 text-[#5C3A1E] font-bold text-center">Status</th>
                    </tr>
                    <tr class="border-b border-[#D4A06A]/50">
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">#</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Attempts</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Total Q</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Correct</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Attempts</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Accuracy</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Time Spent</th>
                        <th class="py-2 px-4 text-[#5C3A1E]/80 text-sm text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="levelStatsBody">
                    <tr>
                        <td colspan="9" class="py-4 text-center text-gray-400">Loading data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ============================================
    // STUDENT PERFORMANCE - TEACHER VIEW
    // ============================================

    let studentId = {{ $studentId ?? 0 }};
    let cycle1Data = [];
    let cycle2Data = [];
    let completionChart = null;
    let timeChart = null;
    let totalLevels = 25;

    // Format time
    function formatTime(seconds) {
        if (!seconds && seconds !== 0) return '--:--';
        const mins = Math.floor(seconds / 60);
        const secs = Math.round(seconds % 60);
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Calculate time taken (60 - time_remaining)
    function calculateTimeTaken(timeRemaining) {
        if (!timeRemaining && timeRemaining !== 0) return null;
        return 60 - timeRemaining;
    }

    // Load student data
    async function loadStudentData() {
        try {
            // Get student info
            const userResponse = await fetch(`/api/user/${studentId}`);
            if (userResponse.ok) {
                const user = await userResponse.json();
                document.getElementById('studentName').textContent = `👤 ${user.name} (${user.username})`;
            }

            // Fetch Cycle 1 results for this student
            const cycle1Response = await fetch(`/api/cycle1/results/${studentId}`);
            if (cycle1Response.ok) {
                cycle1Data = await cycle1Response.json();
                console.log('Cycle 1 data:', cycle1Data);
            }

            // Fetch Cycle 2 results for this student
            const cycle2Response = await fetch(`/api/cycle2/results/${studentId}`);
            if (cycle2Response.ok) {
                cycle2Data = await cycle2Response.json();
                console.log('Cycle 2 data:', cycle2Data);
            }

            processStudentData();
        } catch (error) {
            console.error('Error loading student data:', error);
        }
    }

    // Process student data
    function processStudentData() {
        let completedCount = 0;
        let totalStars = 0;
        let totalAccuracy = 0;
        let accuracyCount = 0;
        let levelData = [];
        let maxLevelWithData = 0;

        // Get unique levels from both cycles
        const levelsFromCycle1 = [...new Set(cycle1Data.map(a => a.level_number))];
        const levelsFromCycle2 = [...new Set(cycle2Data.map(a => a.level_number))];
        const allLevels = [...new Set([...levelsFromCycle1, ...levelsFromCycle2])].sort((a, b) => a - b);
        
        // Find the highest level with any data
        if (allLevels.length > 0) {
            maxLevelWithData = Math.max(...allLevels);
        }

        // Process levels from 1 to totalLevels
        for (let i = 1; i <= totalLevels; i++) {
            const cycle1Attempts = cycle1Data.filter(a => a.level_number === i);
            const cycle2Attempts = cycle2Data.filter(a => a.level_number === i);
            
            // Best Cycle 1 attempt (most questions answered)
            const bestCycle1 = cycle1Attempts.length > 0 ? 
                cycle1Attempts.reduce((best, current) => {
                    return (current.questions_answered > best.questions_answered) ? current : best;
                }, cycle1Attempts[0]) : null;
            
            // Best Cycle 2 attempt (highest accuracy, only completed)
            const completedCycle2 = cycle2Attempts.filter(a => a.is_completed == 1 || a.is_completed === true);
            const bestCycle2 = completedCycle2.length > 0 ?
                completedCycle2.reduce((best, current) => {
                    return (current.accuracy > best.accuracy) ? current : best;
                }, completedCycle2[0]) : null;
            
            // Check if level is completed (has Cycle 1 and completed Cycle 2)
            const isCompleted = cycle1Attempts.length > 0 && bestCycle2 !== null;
            if (isCompleted) completedCount++;

            // Calculate total stars from Cycle 2
            cycle2Attempts.forEach(a => {
                if (a.is_completed == 1 || a.is_completed === true) {
                    totalStars += (a.score || 0);
                }
            });

            // Calculate accuracy from Cycle 2
            cycle2Attempts.forEach(a => {
                if (a.accuracy) {
                    totalAccuracy += a.accuracy;
                    accuracyCount++;
                }
            });

            // Find fastest time from Cycle 2 (from completed attempts)
            let fastestTime = null;
            completedCycle2.forEach(a => {
                const timeTaken = calculateTimeTaken(a.time_remaining);
                if (timeTaken !== null && (fastestTime === null || timeTaken < fastestTime)) {
                    fastestTime = timeTaken;
                }
            });

            // Get best accuracy for Cycle 2
            let bestAccuracy = null;
            if (bestCycle2) {
                bestAccuracy = bestCycle2.accuracy;
            }

            levelData.push({
                level: i,
                cycle1Attempts: cycle1Attempts.length,
                cycle1Best: bestCycle1,
                cycle2Attempts: cycle2Attempts.length,
                cycle2Best: bestCycle2,
                bestAccuracy: bestAccuracy,
                fastestTime: fastestTime,
                isCompleted: isCompleted,
                hasData: cycle1Attempts.length > 0 || cycle2Attempts.length > 0
            });
        }

        // Calculate current level (next uncompleted level)
        let currentLevel = 1;
        for (let i = 0; i < levelData.length; i++) {
            if (!levelData[i].isCompleted && levelData[i].hasData) {
                currentLevel = levelData[i].level;
                break;
            }
            if (levelData[i].isCompleted) {
                currentLevel = levelData[i].level + 1;
            }
        }
        // If all levels completed, show the last level
        if (currentLevel > totalLevels) currentLevel = totalLevels;

        // Update stats
        document.getElementById('completedLevels').textContent = completedCount;
        document.getElementById('totalStars').textContent = totalStars;
        document.getElementById('avgAccuracy').textContent = accuracyCount > 0 ? 
            `${Math.round(totalAccuracy / accuracyCount)}%` : '-';
        document.getElementById('currentLevel').textContent = currentLevel;

        // Update charts
        updateCharts(levelData);
        updateTable(levelData);
    }

    // Update charts
    function updateCharts(levelData) {
        const labels = levelData.map(d => `L${d.level}`);
        const completionData = levelData.map(d => d.isCompleted ? 100 : 0);
        const timeData = levelData.map(d => d.fastestTime !== null ? Math.round(d.fastestTime) : 0);

        // Completion Chart
        const completionCtx = document.getElementById('completionChart').getContext('2d');
        if (completionChart) completionChart.destroy();
        completionChart = new Chart(completionCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Completion (%)',
                    data: completionData,
                    backgroundColor: 'rgba(92, 58, 30, 0.8)',
                    borderColor: '#5C3A1E',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { callback: v => v + '%' }
                    }
                }
            }
        });

        // Time Chart
        const timeCtx = document.getElementById('timeChart').getContext('2d');
        if (timeChart) timeChart.destroy();
        timeChart = new Chart(timeCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Fastest Time (seconds)',
                    data: timeData,
                    borderColor: '#5C3A1E',
                    backgroundColor: 'rgba(92, 58, 30, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#D4A06A',
                    pointBorderColor: '#5C3A1E',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 60,
                        ticks: { callback: v => v + 's' }
                    }
                }
            }
        });
    }

    // Update table
    function updateTable(levelData) {
        const tbody = document.getElementById('levelStatsBody');
        tbody.innerHTML = '';

        let displayCount = 0;
        // Show all levels that have data, up to the max level with data
        const maxLevel = Math.max(...levelData.filter(d => d.hasData).map(d => d.level), 0);

        levelData.forEach(d => {
            // Only show levels that have data or are completed
            if (!d.hasData && !d.isCompleted) return;
            if (d.level > maxLevel + 5) return; // Show a few extra levels after the last with data
            
            displayCount++;
            
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-200 hover:bg-gray-50 transition-colors';

            // Cycle 1 data
            const cycle1Attempts = d.cycle1Attempts || 0;
            const cycle1Total = d.cycle1Best ? d.cycle1Best.questions_answered : 0;
            const cycle1Correct = d.cycle1Best ? d.cycle1Best.correct_answers : 0;
            const cycle1Display = cycle1Total > 0 ? `${cycle1Correct}/${cycle1Total}` : '-';

            // Cycle 2 data
            const cycle2Attempts = d.cycle2Attempts || 0;
            const cycle2Accuracy = d.bestAccuracy !== null ? `${d.bestAccuracy}%` : '-';
            const timeSpentDisplay = d.fastestTime !== null ? formatTime(d.fastestTime) : '-';

            // Status
            let statusText = '';
            let statusColor = '';
            if (d.isCompleted) {
                statusText = '✅ Completed';
                statusColor = 'text-green-600';
            } else if (d.hasData) {
                statusText = '📖 In Progress';
                statusColor = 'text-yellow-600';
            } else {
                statusText = '🔒 Locked';
                statusColor = 'text-gray-400';
            }

            row.innerHTML = `
                <td class="py-3 px-4 text-center font-bold text-[#5C3A1E]">${d.level}</td>
                <td class="py-3 px-4 text-center">${cycle1Attempts}</td>
                <td class="py-3 px-4 text-center">${cycle1Total}</td>
                <td class="py-3 px-4 text-center text-green-600 font-medium">${cycle1Display}</td>
                <td class="py-3 px-4 text-center">${cycle2Attempts}</td>
                <td class="py-3 px-4 text-center text-blue-600 font-medium">${cycle2Accuracy}</td>
                <td class="py-3 px-4 text-center text-purple-600">${timeSpentDisplay}</td>
                <td class="py-3 px-4 text-center font-semibold ${statusColor}">${statusText}</td>
            `;
            tbody.appendChild(row);
        });

        if (displayCount === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="py-4 text-center text-gray-400">No data available for this student</td>
                </tr>
            `;
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadStudentData();
    });
</script>

<style>
    /* Progress circle animation */
    #lessonProgressCircle, #levelProgressCircle {
        transition: stroke-dashoffset 1s ease-in-out;
    }
    
    /* Table styling */
    .table-auto {
        font-size: 14px;
    }
</style>
@endsection