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

        <!-- Simple Header -->
        <div class="text-center mb-8">
            <h1 class="text-5xl md:text-6xl font-bold mb-2" style="color: #EDEDED; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">
                🌲 Math Trail 🌲
            </h1>
            <p class="text-xl md:text-2xl" style="color: #F4A623;">Choose your level!</p>
        </div>

        <!-- Progress Summary -->
        <div class="max-w-3xl mx-auto mb-8">
            <div
                class="bg-[#5C3A1E] rounded-2xl p-4 flex flex-wrap justify-between items-center gap-4 shadow-xl border border-[#8B6914]">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">⭐</span>
                    <div>
                        <p class="text-amber-200/80 text-sm">Total Stars</p>
                        <p class="text-2xl font-bold text-yellow-300" id="totalStars">0</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🏆</span>
                    <div>
                        <p class="text-amber-200/80 text-sm">Levels Done</p>
                        <p class="text-2xl font-bold text-green-300" id="completedLevels">0</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🔥</span>
                    <div>
                        <p class="text-amber-200/80 text-sm">Current Level</p>
                        <p class="text-2xl font-bold text-orange-300" id="currentLevel">1</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Container -->
        <div class="max-w-5xl mx-auto">

            <!-- Row 1: Levels 1-5 -->
            <div class="grid grid-cols-5 gap-5 mb-6">
                @for ($i = 1; $i <= 5; $i++)
                    <div id="level-{{ $i }}-container" class="text-center">
                        <div class="level-node w-full aspect-square rounded-xl shadow-lg flex flex-col items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer"
                            style="background: #8B6914; border: 3px solid #D4A06A;"
                            onclick="selectLevel({{ $i }})">
                            <span class="text-white font-bold text-4xl">{{ $i }}</span>
                        </div>
                        <div class="mt-2 flex justify-center space-x-1" id="level-{{ $i }}-stars">
                            <span class="text-xl">☆</span>
                            <span class="text-xl">☆</span>
                            <span class="text-xl">☆</span>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Row 2: Levels 6-10 -->
            <div class="grid grid-cols-5 gap-5 mb-6">
                @for ($i = 6; $i <= 10; $i++)
                    <div id="level-{{ $i }}-container" class="text-center">
                        <div class="level-node w-full aspect-square rounded-xl shadow-lg flex flex-col items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer opacity-60"
                            style="background: #4A3A28; border: 3px solid #6B5A3A;"
                            onclick="selectLevel({{ $i }})">
                            <span class="text-white/50 font-bold text-4xl">{{ $i }}</span>
                        </div>
                        <div class="mt-2 flex justify-center space-x-1" id="level-{{ $i }}-stars">
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Row 3: Levels 11-15 -->
            <div class="grid grid-cols-5 gap-5 mb-6">
                @for ($i = 11; $i <= 15; $i++)
                    <div id="level-{{ $i }}-container" class="text-center">
                        <div class="level-node w-full aspect-square rounded-xl shadow-lg flex flex-col items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer opacity-60"
                            style="background: #4A3A28; border: 3px solid #6B5A3A;"
                            onclick="selectLevel({{ $i }})">
                            <span class="text-white/50 font-bold text-4xl">{{ $i }}</span>
                        </div>
                        <div class="mt-2 flex justify-center space-x-1" id="level-{{ $i }}-stars">
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Row 4: Levels 16-20 -->
            <div class="grid grid-cols-5 gap-5 mb-6">
                @for ($i = 16; $i <= 20; $i++)
                    <div id="level-{{ $i }}-container" class="text-center">
                        <div class="level-node w-full aspect-square rounded-xl shadow-lg flex flex-col items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer opacity-60"
                            style="background: #4A3A28; border: 3px solid #6B5A3A;"
                            onclick="selectLevel({{ $i }})">
                            <span class="text-white/50 font-bold text-4xl">{{ $i }}</span>
                        </div>
                        <div class="mt-2 flex justify-center space-x-1" id="level-{{ $i }}-stars">
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Row 5: Levels 21-25 -->
            <div class="grid grid-cols-5 gap-5 mt-6">
                @for ($i = 21; $i <= 25; $i++)
                    <div id="level-{{ $i }}-container" class="text-center">
                        <div class="level-node w-full aspect-square rounded-xl shadow-lg flex flex-col items-center justify-center transition-all duration-300 hover:scale-110 cursor-pointer opacity-60"
                            style="background: #4A3A28; border: 3px solid #6B5A3A;"
                            onclick="selectLevel({{ $i }})">
                            <span class="text-white/50 font-bold text-4xl">{{ $i }}</span>
                        </div>
                        <div class="mt-2 flex justify-center space-x-1" id="level-{{ $i }}-stars">
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                            <span class="text-xl text-white/30">☆</span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Simple Decoration -->
        <div class="flex justify-center space-x-8 mt-10">
            <span class="text-4xl">🌴</span>
            <span class="text-4xl">🍃</span>
            <span class="text-4xl">🌿</span>
        </div>
    </div>

    <script>
        // ============================================
        // LEVEL TRACKING SYSTEM USING NEW CYCLE TABLES
        // ============================================

        let cycle1Data = {};
        let cycle2Data = {};
        let userLevelStatus = {};

        // Calculate star rating based on cycle completion
        function calculateStarRating(levelNum) {
            const cycle1Completed = userLevelStatus[levelNum]?.cycle1_completed || false;
            const cycle2Completed = userLevelStatus[levelNum]?.cycle2_completed || false;
            const cycle2Accuracy = userLevelStatus[levelNum]?.cycle2_accuracy || 0;

            if (!cycle1Completed) {
                return 0; // No stars - Cycle 1 not completed
            }

            if (!cycle2Completed) {
                return 1; // 1 star - Only Cycle 1 completed
            }

            // Both cycles completed - check accuracy for 2nd and 3rd star
            if (cycle2Accuracy >= 100) {
                return 3; // 3 stars - Perfect score (100%)
            } else if (cycle2Accuracy >= 80) {
                return 2; // 2 stars - Good score (80-99%)
            } else {
                return 2; // 2 stars - Completed but not perfect
            }
        }

        // Get star display HTML

        function getStarDisplay(stars) {
            if (stars === 3) {
                return '<span class="text-3xl text-yellow-400">★★★</span>'; // Changed from text-xl to text-3xl
            } else if (stars === 2) {
                return '<span class="text-3xl text-yellow-400">★★</span><span class="text-3xl text-white/30">☆</span>';
            } else if (stars === 1) {
                return '<span class="text-3xl text-yellow-400">★</span><span class="text-3xl text-white/30">★★</span>';
            } else {
                return '<span class="text-3xl text-white/30">★★★</span>';
            }
        }

        // Load user progress from new cycle tables
        async function loadUserProgress() {
            try {
                // Fetch Cycle 1 results
                const cycle1Response = await fetch('/api/cycle1/results');
                if (cycle1Response.ok) {
                    const results = await cycle1Response.json();
                    results.forEach(result => {
                        if (!userLevelStatus[result.level_number]) {
                            userLevelStatus[result.level_number] = {};
                        }
                        userLevelStatus[result.level_number].cycle1_completed = true;
                        userLevelStatus[result.level_number].cycle1_attempts = (userLevelStatus[result
                            .level_number].cycle1_attempts || 0) + 1;
                    });
                }

                // Fetch Cycle 2 results
                const cycle2Response = await fetch('/api/cycle2/results');
                if (cycle2Response.ok) {
                    const results = await cycle2Response.json();
                    results.forEach(result => {
                        if (!userLevelStatus[result.level_number]) {
                            userLevelStatus[result.level_number] = {};
                        }
                        userLevelStatus[result.level_number].cycle2_completed = true;
                        const currentBest = userLevelStatus[result.level_number].cycle2_accuracy || 0;
                        if (result.accuracy > currentBest) {
                            userLevelStatus[result.level_number].cycle2_accuracy = result.accuracy;
                        }
                    });
                }

                // Fetch user stats
                const statsResponse = await fetch('/api/user/stats');
                if (statsResponse.ok) {
                    const stats = await statsResponse.json();
                    document.getElementById('totalStars').innerHTML = stats.total_stars || 0;
                    document.getElementById('completedLevels').innerHTML = stats.total_levels_completed || 0;
                    document.getElementById('currentLevel').innerHTML = stats.current_level || 1;
                }

                // Update all levels appearance (1-25)
                for (let i = 1; i <= 25; i++) {
                    updateLevelAppearance(i);
                }

            } catch (error) {
                console.error('Error loading user progress:', error);
                loadFromLocalStorage();
            }
        }

        // Fallback to localStorage
        function loadFromLocalStorage() {
            try {
                const saved = localStorage.getItem('mathQuestProgress');
                if (saved) {
                    const progress = JSON.parse(saved);
                    const completedLevels = progress.completedLevels || [];
                    document.getElementById('totalStars').innerHTML = Object.values(progress.levelStars || {}).reduce((a,
                        b) => a + b, 0);
                    document.getElementById('completedLevels').innerHTML = completedLevels.length;
                    document.getElementById('currentLevel').innerHTML = (completedLevels.length + 1);

                    for (let i = 1; i <= 25; i++) {
                        const isCompleted = completedLevels.includes(i);
                        let stars = 0;
                        if (isCompleted) {
                            const starsEarned = progress.levelStars[i] || 0;
                            stars = starsEarned >= 100 ? 3 : (starsEarned >= 50 ? 2 : 1);
                        }
                        updateLevelUI(i, isCompleted, stars);
                    }
                }
            } catch (e) {
                console.log("No saved progress found");
            }
        }

        // Check if a level is fully completed (both cycles done)
        function isLevelFullyCompleted(levelNum) {
            return userLevelStatus[levelNum]?.cycle1_completed && userLevelStatus[levelNum]?.cycle2_completed;
        }

        // Check if a level is accessible
        function isLevelAccessible(levelNum) {
            if (levelNum === 1) return true;
            return isLevelFullyCompleted(levelNum - 1) || isLevelFullyCompleted(levelNum);
        }

        function updateLevelAppearance(levelNum) {
            const isFullyCompleted = isLevelFullyCompleted(levelNum);
            const isAccessible = isLevelAccessible(levelNum);
            const stars = calculateStarRating(levelNum);

            updateLevelUI(levelNum, isFullyCompleted, isAccessible, stars);
        }

        function updateLevelUI(levelNum, isFullyCompleted, isAccessible, stars) {
            const container = document.getElementById(`level-${levelNum}-container`);
            if (!container) return;

            const node = container.querySelector('.level-node');
            const starsDiv = document.getElementById(`level-${levelNum}-stars`);

            if (starsDiv) {
                starsDiv.innerHTML = getStarDisplay(stars);
            }

            if (isFullyCompleted) {
                node.style.background = '#2D5A1E';
                node.style.border = '3px solid #D4A06A';
                node.style.opacity = '1';
                node.innerHTML = `<span class="text-white font-bold text-4xl">✅</span>`;
                node.onclick = () => selectLevel(levelNum);
            } else if (isAccessible) {
                node.style.background = '#8B6914';
                node.style.border = '3px solid #D4A06A';
                node.style.opacity = '1';
                node.innerHTML = `<span class="text-white font-bold text-4xl">${levelNum}</span>`;
                node.onclick = () => selectLevel(levelNum);
            } else {
                node.style.background = '#4A3A28';
                node.style.border = '3px solid #6B5A3A';
                node.style.opacity = '0.6';
                node.innerHTML = `<span class="text-white/50 font-bold text-4xl">${levelNum}</span>`;
                node.onclick = null;
            }
        }

        function selectLevel(levelNum) {
            const isAccessible = isLevelAccessible(levelNum);

            if (isAccessible) {
                window.location.href = `/game/level-details?level=${levelNum}`;
            } else {
                const previousLevel = levelNum - 1;
                const messageDiv = document.createElement('div');
                messageDiv.className =
                    'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black/80 text-white px-6 py-3 rounded-xl z-50 text-center text-lg';
                messageDiv.innerHTML =
                    `🔒 Complete Level ${previousLevel} first (both cycles) to unlock Level ${levelNum}! 🔒`;
                document.body.appendChild(messageDiv);
                setTimeout(() => messageDiv.remove(), 2500);
            }
        }

        // Load progress when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadUserProgress();
        });
    </script>
@endsection
