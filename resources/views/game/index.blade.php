@extends('layouts.app')

@section('background')
<!-- Game Page Background - Sunny Meadow Theme -->
<div class="page-background">
    <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
        <source src="{{ asset('videos/nature-math-bg2.mp4') }}" type="video/mp4">
        <div class="absolute inset-0 bg-gradient-to-b from-[#87CEEB] via-[#98FB98] to-[#F4A623]"></div>
    </video>
    <div class="absolute inset-0 bg-gradient-to-b from-black/10 to-black/20"></div>
</div>
@endsection

@section('content')
<div class="relative z-10 min-h-screen py-8 px-6">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-5xl md:text-6xl font-bold mb-2" style="color: #EDEDED; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">
            🏆 Math Adventure Trail 🏆
        </h1>
        <p class="text-xl md:text-2xl" style="color: #F4A623;">Follow the trail and collect stars!</p>
    </div>

    <!-- Trail Progress Visualization (Duolingo Style) -->
    <div class="max-w-5xl mx-auto mb-12">
        <div class="relative">
            <!-- The Trail Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-[#E6D07A] to-[#F4A623] rounded-full" 
                 style="top: 40px; bottom: 40px;"></div>
            
            <!-- Trail Nodes (Levels) -->
            <div class="relative flex flex-col items-center space-y-8">
                
                <!-- Level 1 - Completed -->
                <div class="relative w-full">
                    <div class="flex items-center justify-center">
                        <div class="relative group cursor-pointer" onclick="loadLevel(1)">
                            <!-- Glow effect -->
                            <div class="absolute inset-0 rounded-full bg-green-400 blur-xl opacity-60 animate-pulse"></div>
                            <!-- Node -->
                            <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-green-400 to-green-600 border-4 border-white shadow-xl flex items-center justify-center transform transition-all hover:scale-110">
                                <span class="text-4xl">✅</span>
                            </div>
                            <!-- Level info -->
                            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                <p class="text-sm font-bold text-white bg-green-500/80 px-3 py-1 rounded-full">Level 1</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-green-300 font-bold">⭐ 50/50</p>
                    </div>
                </div>
                
                <!-- Connector Line between Level 1 and 2 -->
                <div class="relative w-px h-12 bg-gradient-to-b from-green-400 to-yellow-400"></div>
                
                <!-- Level 2 - In Progress -->
                <div class="relative w-full">
                    <div class="flex items-center justify-center">
                        <div class="relative group cursor-pointer" onclick="loadLevel(2)">
                            <!-- Pulse animation for current level -->
                            <div class="absolute inset-0 rounded-full bg-yellow-400 blur-xl opacity-75 animate-ping"></div>
                            <!-- Node -->
                            <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 border-4 border-white shadow-xl flex items-center justify-center transform transition-all hover:scale-110">
                                <span class="text-4xl">📖</span>
                            </div>
                            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                <p class="text-sm font-bold text-white bg-yellow-500/80 px-3 py-1 rounded-full animate-pulse">Level 2</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-yellow-300 font-bold">⭐ 30/75</p>
                        <div class="w-32 h-2 bg-white/30 rounded-full mx-auto mt-1 overflow-hidden">
                            <div class="h-full rounded-full bg-yellow-400" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Connector Line between Level 2 and 3 -->
                <div class="relative w-px h-12 bg-gradient-to-b from-yellow-400 to-gray-400"></div>
                
                <!-- Level 3 - Locked -->
                <div class="relative w-full opacity-60">
                    <div class="flex items-center justify-center">
                        <div class="relative group">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 border-4 border-white/50 shadow-xl flex items-center justify-center">
                                <span class="text-4xl">🔒</span>
                            </div>
                            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                <p class="text-sm font-bold text-white bg-gray-500/80 px-3 py-1 rounded-full">Level 3</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-gray-400">🔒 Complete Level 2</p>
                    </div>
                </div>
                
                <!-- Connector Line between Level 3 and 4 -->
                <div class="relative w-px h-12 bg-gray-400"></div>
                
                <!-- Level 4 - Locked -->
                <div class="relative w-full opacity-60">
                    <div class="flex items-center justify-center">
                        <div class="relative group">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 border-4 border-white/50 shadow-xl flex items-center justify-center">
                                <span class="text-4xl">🔒</span>
                            </div>
                            <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                <p class="text-sm font-bold text-white bg-gray-500/80 px-3 py-1 rounded-full">Level 4</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-gray-400">🔒 Complete Level 3</p>
                    </div>
                </div>
                
                <!-- Connector Line between Level 4 and 5 -->
                <div class="relative w-px h-12 bg-gray-400"></div>
                
                <!-- Level 5 - Final Boss Level -->
                <div class="relative w-full opacity-60">
                    <div class="flex items-center justify-center">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-400 to-purple-700 border-4 border-purple-300 shadow-xl flex items-center justify-center">
                                <span class="text-5xl">👑</span>
                            </div>
                            <div class="absolute -top-14 left-1/2 transform -translate-x-1/2 whitespace-nowrap">
                                <p class="text-sm font-bold text-white bg-purple-500/80 px-3 py-1 rounded-full">Final Level</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-purple-300/50">👑 Complete all levels</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Game Area -->
    <div class="max-w-4xl mx-auto">
        <div class="rounded-3xl p-8 backdrop-blur-md border-2"
             style="background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%); border-color: rgba(230,208,122,0.5);">
            
            <!-- Current Level Info -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center space-x-3 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                    <span class="text-3xl">🎯</span>
                    <span class="text-2xl font-bold text-white" id="currentLevelName">Level 2: Find the Missing Number</span>
                </div>
            </div>
            
            <!-- Game Stats -->
            <div class="flex justify-between items-center mb-8">
                <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3">
                    <span class="text-3xl">⭐</span>
                    <span class="text-3xl font-bold text-yellow-300" id="score">30</span>
                    <p class="text-sm text-white/70">Current Stars</p>
                </div>
                <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3">
                    <span class="text-3xl">❤️</span>
                    <span class="text-3xl font-bold text-red-300" id="lives">3</span>
                    <p class="text-sm text-white/70">Lives</p>
                </div>
                <div class="text-center bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-3">
                    <span class="text-3xl">🎯</span>
                    <span class="text-3xl font-bold text-blue-300" id="target">75</span>
                    <p class="text-sm text-white/70">Target Stars</p>
                </div>
            </div>

            <!-- Question Area -->
            <div class="text-center mb-8">
                <div class="text-7xl md:text-8xl font-bold mb-4" style="color: #E6D07A; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);" id="question">
                    __ + 9 = 10
                </div>
                <p class="text-xl text-white/50">🌿 Click the correct answer 🌿</p>
            </div>

            <!-- Answer Buttons -->
            <div class="grid grid-cols-2 gap-4 mb-8" id="answers">
                <!-- JavaScript will fill this -->
            </div>

            <!-- Message -->
            <div id="message" class="text-center text-2xl font-bold"></div>
        </div>
    </div>

    <!-- Trail Tips -->
    <div class="max-w-4xl mx-auto mt-8">
        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 text-center border border-white/30">
            <p class="text-white/50">💡 <span id="tip">Complete questions to earn stars and unlock the next level!</span></p>
        </div>
    </div>
</div>

<script>
    // Level Data
    const levels = {
        1: {
            name: "Level 1: Numbers that make 10",
            targetScore: 50,
            tip: "Remember: 1+9=10, 2+8=10, 3+7=10, 4+6=10, 5+5=10!",
            questions: [
                { text: "1 + 9 = ?", answer: 10, options: [8, 9, 10, 11] },
                { text: "2 + 8 = ?", answer: 10, options: [9, 10, 11, 12] },
                { text: "3 + 7 = ?", answer: 10, options: [7, 8, 9, 10] },
                { text: "4 + 6 = ?", answer: 10, options: [8, 9, 10, 11] },
                { text: "5 + 5 = ?", answer: 10, options: [5, 10, 15, 20] }
            ]
        },
        2: {
            name: "Level 2: Find the missing number",
            targetScore: 75,
            tip: "What number plus the given number makes 10? Count on your fingers!",
            questions: [
                { text: "__ + 9 = 10", answer: 1, options: [0, 1, 2, 3] },
                { text: "__ + 8 = 10", answer: 2, options: [1, 2, 3, 4] },
                { text: "__ + 7 = 10", answer: 3, options: [2, 3, 4, 5] },
                { text: "__ + 6 = 10", answer: 4, options: [3, 4, 5, 6] },
                { text: "__ + 5 = 10", answer: 5, options: [4, 5, 6, 7] },
                { text: "__ + 4 = 10", answer: 6, options: [5, 6, 7, 8] },
                { text: "__ + 3 = 10", answer: 7, options: [6, 7, 8, 9] }
            ]
        },
        3: {
            name: "Level 3: Subtraction from 10",
            targetScore: 100,
            tip: "10 - ? = ? Think about what's missing!",
            questions: [
                { text: "10 - 1 = ?", answer: 9, options: [8, 9, 10, 11] },
                { text: "10 - 2 = ?", answer: 8, options: [7, 8, 9, 10] },
                { text: "10 - 3 = ?", answer: 7, options: [6, 7, 8, 9] },
                { text: "10 - 4 = ?", answer: 6, options: [5, 6, 7, 8] },
                { text: "10 - 5 = ?", answer: 5, options: [4, 5, 6, 7] },
                { text: "10 - 6 = ?", answer: 4, options: [3, 4, 5, 6] },
                { text: "10 - 7 = ?", answer: 3, options: [2, 3, 4, 5] }
            ]
        },
        4: {
            name: "Level 4: Mixed Practice",
            targetScore: 125,
            tip: "Mixed addition and subtraction! Take your time!",
            questions: [
                { text: "7 + 3 = ?", answer: 10, options: [8, 9, 10, 11] },
                { text: "10 - 4 = ?", answer: 6, options: [5, 6, 7, 8] },
                { text: "__ + 6 = 10", answer: 4, options: [3, 4, 5, 6] },
                { text: "10 - 2 = ?", answer: 8, options: [7, 8, 9, 10] },
                { text: "8 + __ = 10", answer: 2, options: [1, 2, 3, 4] }
            ]
        },
        5: {
            name: "FINAL LEVEL: Math Champion!",
            targetScore: 150,
            tip: "You're almost there! Show what you've learned!",
            questions: [
                { text: "6 + 4 = ?", answer: 10, options: [9, 10, 11, 12] },
                { text: "10 - 9 = ?", answer: 1, options: [0, 1, 2, 3] },
                { text: "__ + 2 = 10", answer: 8, options: [7, 8, 9, 10] },
                { text: "10 - 0 = ?", answer: 10, options: [8, 9, 10, 11] },
                { text: "3 + __ = 10", answer: 7, options: [6, 7, 8, 9] },
                { text: "10 - 10 = ?", answer: 0, options: [0, 1, 2, 3] },
                { text: "Complete: Making 10 is ___ !", answer: 10, options: [8, 9, 10, 11], special: true }
            ]
        }
    };

    let currentLevel = 2; // Start at level 2 (in progress)
    let currentQuestionIndex = 0;
    let score = 30;
    let lives = 3;
    let active = true;
    let currentQuestions = [];

    function loadLevel(level) {
        if (!levels[level]) return;
        
        // Check if level is unlocked
        if (level > currentLevel) {
            document.getElementById('message').innerHTML = '🔒 Complete the current level first! 🔒';
            document.getElementById('message').style.color = '#F4A623';
            setTimeout(() => {
                document.getElementById('message').innerHTML = '';
            }, 2000);
            return;
        }
        
        currentLevel = level;
        currentQuestions = [...levels[level].questions];
        currentQuestionIndex = 0;
        score = 0;
        lives = 3;
        active = true;
        
        document.getElementById('score').innerHTML = score;
        document.getElementById('lives').innerHTML = lives;
        document.getElementById('target').innerHTML = levels[level].targetScore;
        document.getElementById('currentLevelName').innerHTML = levels[level].name;
        document.getElementById('tip').innerHTML = levels[level].tip;
        
        loadQuestion();
    }

    function loadQuestion() {
        if (!active) return;
        
        if (currentQuestionIndex >= currentQuestions.length) {
            // Level complete!
            if (score >= levels[currentLevel].targetScore) {
                // Level passed!
                if (currentLevel < 5) {
                    currentLevel++;
                }
                document.getElementById('message').innerHTML = '🎉 Level Complete! 🎉<br>You earned ' + score + ' stars!';
                document.getElementById('message').style.color = '#E6D07A';
                document.getElementById('message').style.fontSize = '28px';
                active = false;
                
                // Show next level button
                setTimeout(() => {
                    if (currentLevel <= 5) {
                        document.getElementById('message').innerHTML += '<br><button onclick="loadLevel(' + currentLevel + ')" class="mt-4 bg-green-500 text-white px-8 py-3 rounded-full text-xl font-bold hover:scale-105 transition-transform">▶ Continue to Next Level</button>';
                    } else {
                        document.getElementById('message').innerHTML = '<br>🏆 CONGRATULATIONS! You completed all levels! 🏆';
                    }
                }, 1000);
            } else {
                document.getElementById('message').innerHTML = '😅 Try again! You need ' + (levels[currentLevel].targetScore - score) + ' more stars!';
                document.getElementById('message').style.color = '#F4A623';
                active = false;
                setTimeout(() => {
                    document.getElementById('message').innerHTML += '<br><button onclick="loadLevel(' + currentLevel + ')" class="mt-4 bg-yellow-500 text-white px-8 py-3 rounded-full text-xl font-bold hover:scale-105 transition-transform">🔄 Try Level Again</button>';
                }, 1000);
            }
            return;
        }

        const q = currentQuestions[currentQuestionIndex];
        document.getElementById('question').innerHTML = q.text;
        
        const shuffled = [...q.options];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        
        const answersDiv = document.getElementById('answers');
        answersDiv.innerHTML = '';
        
        shuffled.forEach(opt => {
            const btn = document.createElement('button');
            btn.className = 'bg-white/20 backdrop-blur-sm hover:bg-white/30 text-3xl md:text-4xl font-bold p-6 rounded-2xl transition-all hover:scale-105 border-2 border-white/30';
            btn.style.color = '#E6D07A';
            btn.innerHTML = opt;
            btn.onclick = () => checkAnswer(opt, q.answer);
            answersDiv.appendChild(btn);
        });
        
        document.getElementById('message').innerHTML = '';
    }

    function checkAnswer(selected, correct) {
        if (!active) return;
        
        if (selected === correct) {
            score += 10;
            document.getElementById('score').innerHTML = score;
            document.getElementById('message').innerHTML = '✅ Great! +10 stars! 🌟';
            document.getElementById('message').style.color = '#E6D07A';
            currentQuestionIndex++;
            setTimeout(loadQuestion, 800);
        } else {
            lives--;
            document.getElementById('lives').innerHTML = lives;
            document.getElementById('message').innerHTML = '❌ Oops! Try again! 🎈';
            document.getElementById('message').style.color = '#F4A623';
            
            if (lives <= 0) {
                active = false;
                document.getElementById('message').innerHTML = '💔 Game over! You got ' + score + ' stars. Try again! 💔<br><button onclick="loadLevel(' + currentLevel + ')" class="mt-4 bg-green-500 text-white px-8 py-3 rounded-full text-xl font-bold hover:scale-105 transition-transform">🔄 Try Again</button>';
                document.getElementById('message').style.color = '#E6D07A';
            } else {
                setTimeout(() => {
                    document.getElementById('message').innerHTML = '';
                }, 800);
            }
        }
    }

    // Initialize the game
    loadLevel(2);
    
    // Add click transitions to all links
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') && !this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (typeof navigateWithTransition === 'function') {
                    navigateWithTransition(url, this);
                } else {
                    window.location.href = url;
                }
            }
        });
    });
</script>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes ping {
        0% { transform: scale(1); opacity: 1; }
        75%, 100% { transform: scale(1.5); opacity: 0; }
    }
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
    .animate-float-delayed {
        animation: float 4s ease-in-out infinite 2s;
    }
    .animate-bounce {
        animation: bounce 2s ease-in-out infinite;
        display: inline-block;
    }
    .animate-spin-slow {
        animation: spin-slow 20s linear infinite;
    }
    .animate-ping {
        animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
</style>
@endsection