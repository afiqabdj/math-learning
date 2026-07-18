@extends('layouts.app')

@section('background')
    <div class="page-background">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
            <source src="{{ asset('videos/nature-math-bg.mp4') }}" type="video/mp4">
            <div class="absolute inset-0 bg-gradient-to-b from-[#6B8E23] via-[#556B2F] to-[#2E4A1E]"></div>
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-black/40"></div>

        <div class="absolute bottom-0 left-0 text-9xl opacity-20">🌲</div>
        <div class="absolute top-20 right-10 text-8xl opacity-20">🌳</div>
        <div class="absolute bottom-10 right-20 text-7xl opacity-20">🍄</div>
    </div>
@endsection

@section('content')
    <div class="relative z-10 min-h-screen py-12 px-6">

        <div class="text-center mb-12">
            <h1 class="text-6xl font-bold mb-3" style="color: #EDEDED; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">
                🌲 Learning Trail 🌲
            </h1>
            <p class="text-2xl" style="color: #E6D07A;">Follow the path through the forest!</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Lesson 1 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(1)"
                style="background: linear-gradient(135deg, rgba(122,139,59,0.95) 0%, rgba(85,107,47,0.95) 100%); border-left: 8px solid #E6D07A;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson1-icon">🔢</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson1-title">Lesson 1: Numbers 0-9</h2>
                            <p class="text-white/80">Learn to add numbers from 0 to 9</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson1-stars">⭐ 0/20</div>
                            <div class="text-sm text-white/70" id="lesson1-status">Not started</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(1)" id="lesson1-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105"
                            style="background: #E6D07A; color: #556B2F;">Start →</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#E6D07A]" id="lesson1-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 2 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(2)"
                style="background: linear-gradient(135deg, rgba(126,141,168,0.95) 0%, rgba(109,121,148,0.95) 100%); border-left: 8px solid #F4A623;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson2-icon">🔟</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson2-title">Lesson 2: Numbers 0-10</h2>
                            <p class="text-white/80">Learn to add numbers up to 10</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson2-stars">⭐ 0/20</div>
                            <div class="text-sm text-white/70" id="lesson2-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(2)" id="lesson2-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#F4A623]" id="lesson2-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 3 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(3)"
                style="background: linear-gradient(135deg, rgba(140,157,179,0.95) 0%, rgba(125,143,168,0.95) 100%); border-left: 8px solid #8C9DB3;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson3-icon">🧮</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson3-title">Lesson 3: Adding Three Numbers
                            </h2>
                            <p class="text-white/80">Learn how three numbers add up to 10</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson3-stars">⭐ 0/20</div>
                            <div class="text-sm text-white/70" id="lesson3-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(3)" id="lesson3-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#8C9DB3]" id="lesson3-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 4 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(4)"
                style="background: linear-gradient(135deg, rgba(159,197,207,0.95) 0%, rgba(140,157,179,0.95) 100%); border-left: 8px solid #E6D07A;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson4-icon">🔟</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson4-title">Lesson 4: Teen Numbers)</h2>
                            <p class="text-white/80">Learn numbers from 11 to 19</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson4-stars">⭐ 0/20</div>
                            <div class="text-sm text-white/70" id="lesson4-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(4)" id="lesson4-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#E6D07A]" id="lesson4-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 5 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(5)"
                style="background: linear-gradient(135deg, rgba(122,139,59,0.95) 0%, rgba(85,107,47,0.95) 100%); border-left: 8px solid #E6D07A;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson5-icon">➖</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson5-title">Lesson 5: Subtraction (Below 10)
                            </h2>
                            <p class="text-white/80">Learn to subtract numbers below 10</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson5-stars">⭐ 0/20</div>
                            <div class="text-sm text-white/70" id="lesson5-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(5)" id="lesson5-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#E6D07A]" id="lesson5-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 6 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(6)"
                style="background: linear-gradient(135deg, rgba(159,197,207,0.95) 0%, rgba(140,157,179,0.95) 100%); border-left: 8px solid #F4A623;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson6-icon">🎯</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson6-title">Lesson 6: Bridging to 10
                                Technique</h2>
                            <p class="text-white/80">Learn the secret trick to add numbers faster!</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson6-stars">⭐ 0/16</div>
                            <div class="text-sm text-white/70" id="lesson6-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(6)" id="lesson6-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#F4A623]" id="lesson6-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 7 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(7)"
                style="background: linear-gradient(135deg, rgba(122,139,59,0.95) 0%, rgba(85,107,47,0.95) 100%); border-left: 8px solid #E6D07A;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson7-icon">🔟</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson7-title">Lesson 7: Adding Teen Numbers
                            </h2>
                            <p class="text-white/80">Learn to add teen numbers (10-19) + numbers up to 10</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson7-stars">⭐ 0/24</div>
                            <div class="text-sm text-white/70" id="lesson7-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(7)" id="lesson7-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#E6D07A]" id="lesson7-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 8 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(8)"
                style="background: linear-gradient(135deg, rgba(126,141,168,0.95) 0%, rgba(109,121,148,0.95) 100%); border-left: 8px solid #F4A623;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson8-icon">➕</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson8-title">Lesson 8: Teen + Teen Numbers
                            </h2>
                            <p class="text-white/80">Learn to add teen numbers to teen numbers</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson8-stars">⭐ 0/32</div>
                            <div class="text-sm text-white/70" id="lesson8-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(8)" id="lesson8-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#F4A623]" id="lesson8-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 9 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(9)"
                style="background: linear-gradient(135deg, rgba(159,197,207,0.95) 0%, rgba(140,157,179,0.95) 100%); border-left: 8px solid #E6D07A;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson9-icon">💪</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson9-title">Lesson 9: Adding Two-Digit
                                Numbers</h2>
                            <p class="text-white/80">Learn to add bigger two-digit numbers</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson9-stars">⭐ 0/32</div>
                            <div class="text-sm text-white/70" id="lesson9-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(9)" id="lesson9-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#E6D07A]" id="lesson9-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 10 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(10)"
                style="background: linear-gradient(135deg, rgba(122,139,59,0.95) 0%, rgba(85,107,47,0.95) 100%); border-left: 8px solid #E6D07A;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson10-icon">➖</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson10-title">Lesson 10: Subtracting Same Tens
                            </h2>
                            <p class="text-white/80">Learn to subtract numbers with the same tens</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson10-stars">⭐ 0/28</div>
                            <div class="text-sm text-white/70" id="lesson10-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(10)" id="lesson10-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#E6D07A]" id="lesson10-progress" style="width: 0%"></div>
                </div>
            </div>

            <!-- Lesson 11 -->
            <div class="group relative overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 cursor-pointer"
                onclick="goToLesson(11)"
                style="background: linear-gradient(135deg, rgba(126,141,168,0.95) 0%, rgba(109,121,148,0.95) 100%); border-left: 8px solid #F4A623;">
                <div class="p-6 flex items-center justify-between flex-wrap md:flex-nowrap gap-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-6xl" id="lesson11-icon">🧮</span>
                        <div>
                            <h2 class="text-3xl font-bold text-white" id="lesson11-title">Lesson 11: Subtracting Two Big
                                Numbers</h2>
                            <p class="text-white/80">Learn to subtract when you need to borrow</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-2xl font-bold text-yellow-300" id="lesson11-stars">⭐ 0/32</div>
                            <div class="text-sm text-white/70" id="lesson11-status">Locked</div>
                        </div>
                        <button onclick="event.stopPropagation(); goToLesson(11)" id="lesson11-btn"
                            class="px-6 py-3 rounded-full font-bold transition-all hover:scale-105 opacity-50 cursor-not-allowed"
                            style="background: #8C9DB3; color: #556B2F;" disabled>Locked 🔒</button>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-white/20">
                    <div class="h-full rounded-full bg-[#F4A623]" id="lesson11-progress" style="width: 0%"></div>
                </div>
            </div>

            <div class="flex justify-center space-x-12 mt-12">
                <div class="text-center animate-float">
                    <span class="text-7xl drop-shadow-lg">🦉</span>
                    <p class="text-sm font-bold mt-2" style="color: #E6D07A;">"Keep learning!"</p>
                </div>
                <div class="text-center animate-float" style="animation-delay: 0.3s;">
                    <span class="text-7xl drop-shadow-lg">🐿️</span>
                    <p class="text-sm font-bold mt-2" style="color: #E6D07A;">"You're doing great!"</p>
                </div>
                <div class="text-center animate-float" style="animation-delay: 0.6s;">
                    <span class="text-7xl drop-shadow-lg">🦔</span>
                    <p class="text-sm font-bold mt-2" style="color: #E6D07A;">"Almost there!"</p>
                </div>
            </div>
        </div>

        <script>
    // Track completed lessons
    let completedLessons = [];
    let allLessonProgress = [];

    // Load lesson progress from database
    async function loadLessonProgress() {
        try {
            const response = await fetch('/api/lesson-progress');
            if (response.ok) {
                const data = await response.json();
                console.log('📊 Lesson progress data from server:', data);
                
                // Handle the response format
                if (data.completed_lessons) {
                    completedLessons = data.completed_lessons || [];
                } else if (Array.isArray(data)) {
                    completedLessons = data;
                } else if (data.all_progress) {
                    allLessonProgress = data.all_progress;
                    completedLessons = allLessonProgress.filter(p => p.is_completed).map(p => p.lesson_number);
                } else {
                    completedLessons = [];
                }
                
                console.log('✅ Completed lessons:', completedLessons);
                updateAllLessonUI();
            } else {
                console.error('Failed to load lesson progress');
            }
        } catch (error) {
            console.error('Error loading lesson progress:', error);
        }
    }

    // Update UI for all lessons based on completed lessons
    function updateAllLessonUI() {
        console.log('🔄 Updating UI for all lessons...');
        
        // Lesson 1 is always available
        const isLesson1Completed = completedLessons.includes(1);
        console.log('📘 Lesson 1 completed:', isLesson1Completed);
        updateLessonUI(1, true, isLesson1Completed);

        // Update lessons 2-11 based on previous lesson completion
        for (let i = 2; i <= 11; i++) {
            const isPreviousCompleted = completedLessons.includes(i - 1);
            const isCurrentCompleted = completedLessons.includes(i);
            updateLessonUI(i, isPreviousCompleted, isCurrentCompleted);
        }
    }

    // Update individual lesson UI
    function updateLessonUI(lessonNum, isUnlocked, isCompleted) {
        const starsSpan = document.getElementById(`lesson${lessonNum}-stars`);
        const statusSpan = document.getElementById(`lesson${lessonNum}-status`);
        const progressBar = document.getElementById(`lesson${lessonNum}-progress`);
        const iconSpan = document.getElementById(`lesson${lessonNum}-icon`);
        const btn = document.getElementById(`lesson${lessonNum}-btn`);
        
        console.log(`📚 Updating lesson ${lessonNum}: unlocked=${isUnlocked}, completed=${isCompleted}, button exists: ${!!btn}`);

        if (isCompleted) {
            // Completed lesson
            console.log(`✅ Lesson ${lessonNum} is COMPLETED`);
            if (iconSpan) iconSpan.innerHTML = '✅';
            if (starsSpan) starsSpan.innerHTML = '⭐ 20/20';
            if (statusSpan) statusSpan.innerHTML = 'Completed!';
            if (progressBar) progressBar.style.width = '100%';
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.style.background = '#E6D07A';
                btn.style.color = '#556B2F';
                btn.innerHTML = 'Review →';
                console.log(`🔘 Lesson ${lessonNum} button set to: Review →`);
            }
        } else if (isUnlocked) {
            // Unlocked but not completed
            console.log(`🔓 Lesson ${lessonNum} is UNLOCKED but not completed`);
            if (iconSpan) iconSpan.innerHTML = getLessonIcon(lessonNum);
            if (starsSpan) starsSpan.innerHTML = '⭐ 0/20';
            if (statusSpan) statusSpan.innerHTML = 'Ready to start!';
            if (progressBar) progressBar.style.width = '0%';
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.style.background = '#E6D07A';
                btn.style.color = '#556B2F';
                btn.innerHTML = 'Start →';
                console.log(`🔘 Lesson ${lessonNum} button set to: Start →`);
            }
        } else {
            // Locked lesson
            console.log(`🔒 Lesson ${lessonNum} is LOCKED`);
            if (iconSpan) iconSpan.innerHTML = '🔒';
            if (starsSpan) starsSpan.innerHTML = '⭐ 0/20';
            if (statusSpan) statusSpan.innerHTML = 'Locked';
            if (progressBar) progressBar.style.width = '0%';
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.style.background = '#8C9DB3';
                btn.style.color = '#556B2F';
                btn.innerHTML = 'Locked 🔒';
                console.log(`🔘 Lesson ${lessonNum} button set to: Locked 🔒`);
            }
        }
    }

    function getLessonIcon(lessonNum) {
        const icons = {
            1: '🔢',
            2: '🔟',
            3: '🧮',
            4: '🔟',
            5: '➖',
            6: '🎯',
            7: '🔟',
            8: '➕',
            9: '💪',
            10: '➖',
            11: '🧮'
        };
        return icons[lessonNum] || '📚';
    }

    function goToLesson(lessonId) {
        // Lesson 1 is always accessible
        if (lessonId === 1) {
            window.location.href = `/lessons/lesson${lessonId}`;
            return;
        }

        // For lessons 2-11, check if previous lesson is completed
        const isPreviousCompleted = completedLessons.includes(lessonId - 1);

        if (isPreviousCompleted) {
            window.location.href = `/lessons/lesson${lessonId}`;
        } else {
            // Show error message
            const messageDiv = document.createElement('div');
            messageDiv.className =
                'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-black/80 text-white px-6 py-3 rounded-xl z-50 text-center text-lg';
            messageDiv.innerHTML = `🔒 Complete Lesson ${lessonId - 1} first to unlock Lesson ${lessonId}! 🔒`;
            document.body.appendChild(messageDiv);
            setTimeout(() => messageDiv.remove(), 2500);
        }
    }

    // Mark lesson as completed in database
    async function completeLesson(lessonNum) {
        try {
            const response = await fetch('/api/lesson-complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    lesson_number: lessonNum
                })
            });

            if (response.ok) {
                console.log(`✅ Lesson ${lessonNum} completed successfully!`);
                await loadLessonProgress();
            }
        } catch (error) {
            console.error('Error completing lesson:', error);
        }
    }

    // For debugging - manually check what's in the database
    async function checkDatabase() {
        try {
            const response = await fetch('/api/lesson-progress');
            const data = await response.json();
            console.log('📊 Database contents:', data);
        } catch (error) {
            console.error('Error fetching:', error);
        }
    }

    // Load progress on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Page loaded, fetching lesson progress...');
        loadLessonProgress();
        
        // Also check database after 1 second
        setTimeout(() => {
            checkDatabase();
        }, 1000);
    });

    // Expose functions to global scope for debugging
    window.refreshLessons = loadLessonProgress;
    window.checkDatabase = checkDatabase;
    window.completeLessonManually = completeLesson;

    // Add transition to all links
    document.querySelectorAll('a, .cursor-pointer').forEach(element => {
        element.addEventListener('click', function(e) {
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

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }
            }

            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
        </style>
    </div>
@endsection
