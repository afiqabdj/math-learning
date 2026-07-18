@extends('layouts.teachers')

@section('content')
    <div class="relative z-10 min-h-screen flex">

        <!-- Left Sidebar Navigation - White -->
        <div class="w-64 bg-white shadow-2xl flex-shrink-0 hidden md:block">
            <div class="p-6">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-2">👩‍🏫</div>
                    <h2 class="text-[#5C3A1E] font-bold text-xl">Teacher Portal</h2>
                    <p class="text-gray-500 text-sm">Math Quest Academy</p>
                    <div class="text-sm text-gray-500">
                        🕐 {{ now()->format('l, F j, Y h:i:s A') }}
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="#"
                        class="flex items-center space-x-3 bg-[#5C3A1E] text-white px-4 py-3 rounded-xl transition-all shadow-md">
                        <span class="text-2xl">📊</span>
                        <span class="font-semibold">Dashboard</span>
                    </a>

                    <a href="{{ route('teacher.questions.index') }}"
                        class="flex items-center space-x-3 {{ request()->routeIs('teacher.questions.*') ? 'bg-[#5C3A1E] text-white' : 'text-gray-600 hover:bg-[#5C3A1E]/10' }} px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">📝</span>
                        <span class="font-semibold">Assignments</span>
                    </a>
                    <a href="{{ route('teacher.students') }}"
                        class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">👨‍🎓</span>
                        <span class="font-semibold">Students</span>
                    </a>
                    <a href="{{ route('teacher.teachers') }}"
                        class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">👨‍🏫</span>
                        <span class="font-semibold">Teachers</span>
                    </a>
                </nav>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-[#5C3A1E]/20 flex items-center justify-center text-2xl">
                            📕
                        </div>
                        <div>
                            <p class="text-[#5C3A1E] font-semibold text-sm">{{ Auth::user()->name ?? 'Teacher' }}</p>
                            <p class="text-gray-400 text-xs">Online</p>
                        </div>
                    </div>
                    <a href="/logout"
                        class="mt-4 flex items-center space-x-2 text-gray-400 hover:text-[#5C3A1E] text-sm transition-all">
                        <span>🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white shadow-2xl border-t border-gray-200 z-50">
            <div class="flex justify-around py-3">
                <a href="#" class="text-[#5C3A1E] text-2xl">📊</a>
                <a href="#" class="text-gray-400 text-2xl">📈</a>
                <a href="#" class="text-gray-400 text-2xl">🏆</a>
                <a href="#" class="text-gray-400 text-2xl">⚙️</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-h-screen pb-20 md:pb-8">
            <div class="p-4 md:p-8">

                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                                <span>📊</span>
                                <span>Teacher Dashboard</span>
                            </h1>
                            <p class="text-white/70 text-sm mt-1">Track student progress across all levels and cycles</p>
                        </div>
                        <div class="flex items-center gap-3 mt-4 md:mt-0">
                            <button onclick="location.reload()"
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm transition-all flex items-center gap-2">
                                <span>🔄</span> Refresh
                            </button>
                            <button onclick="window.location.href='/game/levels'"
                                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm transition-all flex items-center gap-2">
                                <span>📚</span> Go to Game
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-white rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Total Students</p>
                                <p class="text-3xl font-bold text-[#5C3A1E] mt-1" id="totalStudents">0</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-3xl">👨‍🎓
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Students This Year</p>
                                <p class="text-3xl font-bold text-[#F4A623] mt-1" id="studentsThisYear">0</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-3xl">📅
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RAW DATA DISPLAY - DEBUG -->
                <div class="bg-white rounded-2xl p-6 shadow-xl mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#5C3A1E] font-bold text-lg">📋 Raw Data from Database</h3>
                        <span id="recordCount" class="text-gray-500 text-sm">0 records</span>
                    </div>
                    <div id="rawDataContainer" class="bg-gray-50 rounded-lg p-4 max-h-60 overflow-auto">
                        <p class="text-gray-400 text-center">Loading data...</p>
                    </div>
                </div>

                <!-- Line Chart - No Filter -->
                <div class="bg-white rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#5C3A1E] font-bold text-lg">📈 Average Time Taken per Level (Cycle 2)</h3>
                        <button id="refreshChartBtn"
                            class="bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-4 py-2 rounded-lg font-bold transition-all text-sm">
                            🔄 Refresh Chart
                        </button>
                    </div>
                    <div class="h-80 w-full">
                        <canvas id="timeChart"></canvas>
                    </div>
                    <p class="text-gray-400 text-xs text-center mt-2">Average time taken per level (seconds) - Maximum 60
                        seconds</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ============================================
        // TEACHER DASHBOARD - NO FILTERS, ALL DATA
        // ============================================

        let allCycle2Results = [];
        let timeChartInstance = null;

        // Fetch student stats
        async function fetchStudentStats() {
            try {
                const response = await fetch('/api/students/stats');
                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('totalStudents').textContent = data.total || 0;
                    document.getElementById('studentsThisYear').textContent = data.thisYear || 0;
                    console.log('✅ Student stats loaded');
                }
            } catch (error) {
                console.error('Error fetching student stats:', error);
            }
        }

        // Fetch Cycle 2 results - Using TEACHER endpoint (gets ALL results)
        async function fetchCycle2Results() {
            try {
                console.log('🔄 Fetching Cycle 2 results for teacher...');

                const response = await fetch('/api/teacher/cycle2/results');

                if (response.ok) {
                    allCycle2Results = await response.json();
                    console.log('✅ Cycle 2 Results:', allCycle2Results.length, 'records');

                    if (allCycle2Results.length > 0) {
                        console.log('📊 First record:', allCycle2Results[0]);
                        const uniqueLevels = [...new Set(allCycle2Results.map(r => parseInt(r.level_number)))].sort((a,
                            b) => a - b);
                        console.log('📊 Levels found:', uniqueLevels);
                    }

                    // Display raw data
                    displayRawData(allCycle2Results);
                    document.getElementById('recordCount').textContent = allCycle2Results.length + ' records';

                } else {
                    console.error('❌ Failed to fetch. Status:', response.status);
                    document.getElementById('rawDataContainer').innerHTML = `
                        <div class="text-red-500 p-4">
                            <p>❌ Failed to fetch data. Status: ${response.status}</p>
                            <p class="text-sm mt-2">Make sure you are logged in as a teacher.</p>
                        </div>
                    `;
                }

                updateChart();
            } catch (error) {
                console.error('❌ Error fetching:', error);
                document.getElementById('rawDataContainer').innerHTML = `
                    <div class="text-red-500 p-4">
                        <p>❌ Error: ${error.message}</p>
                    </div>
                `;
            }
        }

        // Display raw data in the container
        function displayRawData(data) {
            const container = document.getElementById('rawDataContainer');

            if (!data || data.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center">📭 No data found in database</p>';
                return;
            }

            let html = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Remaining</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Taken</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Accuracy</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

            const displayData = data.slice(0, 20);
            displayData.forEach(r => {
                const timeTaken = 60 - (parseFloat(r.time_remaining) || 0);
                html += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm text-gray-900">${r.id || 'N/A'}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">${r.user_id || 'N/A'}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">${r.level_number || 'N/A'}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">${r.time_remaining || '0'}s</td>
                        <td class="px-4 py-2 text-sm font-bold ${timeTaken > 50 ? 'text-green-600' : timeTaken > 30 ? 'text-yellow-600' : 'text-red-600'}">${Math.round(timeTaken)}s</td>
                        <td class="px-4 py-2 text-sm">${r.is_completed ? '✅ Yes' : '❌ No'}</td>
                        <td class="px-4 py-2 text-sm">${r.accuracy || '0'}%</td>
                    </tr>
                `;
            });

            if (data.length > 20) {
                html += `
                    <tr>
                        <td colspan="7" class="px-4 py-2 text-sm text-gray-400 text-center">
                            ... and ${data.length - 20} more records
                        </td>
                    </tr>
                `;
            }

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            container.innerHTML = html;
        }

        // Update chart - shows ALL data
        function updateChart() {
            console.log('📈 updateChart() called');
            console.log('📊 Total records:', allCycle2Results.length);

            if (allCycle2Results.length === 0) {
                console.warn('⚠️ No data available');
                showNoDataChart('No Cycle 2 data available');
                return;
            }

            // Group by level
            const levelData = {};
            allCycle2Results.forEach(r => {
                const level = parseInt(r.level_number);
                const timeRemaining = parseFloat(r.time_remaining) || 0;
                const timeTaken = 60 - timeRemaining;

                if (timeTaken >= 0 && timeTaken <= 60) {
                    if (!levelData[level]) {
                        levelData[level] = {
                            totalTime: 0,
                            count: 0
                        };
                    }
                    levelData[level].totalTime += timeTaken;
                    levelData[level].count++;
                }
            });

            const levels = Object.keys(levelData).map(Number).sort((a, b) => a - b);
            console.log('📊 Levels with data:', levels);

            if (levels.length === 0) {
                console.warn('⚠️ No valid data');
                showNoDataChart('No valid time data available');
                return;
            }

            const labels = levels.map(l => `Level ${l}`);
            const data = levels.map(l => Math.round(levelData[l].totalTime / levelData[l].count));

            console.log('📊 Labels:', labels);
            console.log('📊 Data:', data);

            // Create chart
            const canvas = document.getElementById('timeChart');
            if (!canvas) {
                console.error('❌ Canvas not found');
                return;
            }

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('❌ Context not found');
                return;
            }

            if (timeChartInstance) {
                timeChartInstance.destroy();
                timeChartInstance = null;
            }

            try {
                timeChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Time Taken (seconds)',
                            data: data,
                            borderColor: '#5C3A1E',
                            backgroundColor: 'rgba(92, 58, 30, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#D4A06A',
                            pointBorderColor: '#5C3A1E',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#5C3A1E',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Average Time: ${context.parsed.y}s`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 60,
                                title: {
                                    display: true,
                                    text: 'Time (seconds)',
                                    color: '#5C3A1E',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    color: '#5C3A1E',
                                    callback: function(value) {
                                        return value + 's';
                                    },
                                    stepSize: 10
                                },
                                grid: {
                                    color: 'rgba(92, 58, 30, 0.1)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Level',
                                    color: '#5C3A1E',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    color: '#5C3A1E',
                                    maxRotation: 45,
                                    minRotation: 30
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                console.log('✅ Chart created successfully!');
            } catch (error) {
                console.error('❌ Error creating chart:', error);
            }
        }

        function showNoDataChart(message) {
            const canvas = document.getElementById('timeChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            if (!ctx) return;

            if (timeChartInstance) {
                timeChartInstance.destroy();
                timeChartInstance = null;
            }

            timeChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['No Data'],
                    datasets: [{
                        label: message || 'No Data Available',
                        data: [0],
                        borderColor: '#999',
                        backgroundColor: 'rgba(153, 153, 153, 0.1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#5C3A1E'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 60,
                            ticks: {
                                color: '#5C3A1E'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#5C3A1E'
                            }
                        }
                    }
                }
            });
        }

        // ============================================
        // INITIALIZATION
        // ============================================

        document.addEventListener('DOMContentLoaded', function() {
            console.log('📄 DOM loaded');
            fetchStudentStats();
            fetchCycle2Results();

            // Refresh button
            const refreshBtn = document.getElementById('refreshChartBtn');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    console.log('🔄 Refresh button clicked');
                    fetchCycle2Results();
                });
            }
        });

        // Auto-refresh every 60 seconds
        setInterval(() => {
            console.log('🔄 Auto-refresh');
            fetchStudentStats();
            fetchCycle2Results();
        }, 60000);

        // Make functions global
        window.updateChart = updateChart;
        window.fetchCycle2Results = fetchCycle2Results;
        window.displayRawData = displayRawData;

        console.log('✅ Dashboard loaded');
    </script>
@endsection
