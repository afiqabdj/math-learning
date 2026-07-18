@extends('layouts.app')

@section('background')
<div class="page-background">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
        <source src="{{ asset('videos/nature-math-bg2.mp4') }}" type="video/mp4">
        <div class="absolute inset-0 bg-gradient-to-b from-[#6B8E23] via-[#556B2F] to-[#2E4A1E]"></div>
    </video>
    <div class="absolute inset-0 bg-black/30"></div>
</div>
@endsection

@section('content')
<div class="relative z-10 min-h-screen py-8 px-4">

    <div class="max-w-4xl mx-auto mb-6">
        <a href="/lessons" class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md hover:bg-white/30 px-4 py-2 rounded-full transition-all">
            <span>←</span>
            <span>Back to Lessons</span>
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-[#FDF8F0] rounded-3xl border-4 border-[#E8D5A3] shadow-2xl overflow-hidden">
            
            <div class="h-3 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>

            <div class="text-center pt-8 pb-4 px-6">
                <div class="inline-flex items-center space-x-3 bg-[#F0E8D0] rounded-full px-6 py-3 shadow-inner">
                    <span class="text-3xl">🔟</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 4: Numbers 10-19 (Teen Numbers)</span>
                </div>
            </div>

            <div class="px-8 mb-4">
                <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                    <span>Lesson Progress</span>
                    <span id="progressText">Slide 1/20</span>
                </div>
                <div class="bg-[#E8D5A3] rounded-full h-3 overflow-hidden">
                    <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-500" style="width: 5%"></div>
                </div>
            </div>

            <div class="p-8 min-h-[500px]">
                <div id="contentArea"></div>
            </div>

            <div class="flex justify-between px-8 pb-8">
                <button id="prevBtn" onclick="previousSlide()" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-6 py-2 rounded-full font-bold transition-all hover:scale-105 shadow-md hidden">
                    ← Previous
                </button>
                <button id="nextBtn" onclick="nextSlide()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-6 py-2 rounded-full font-bold transition-all hover:scale-105 shadow-md ml-auto">
                    Next →
                </button>
            </div>

            <div class="h-2 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>
        </div>
    </div>

    <div class="text-center mt-8">
        <div class="inline-flex items-center space-x-3 bg-[#FDF8F0]/90 rounded-full px-6 py-3 shadow-lg border border-[#E8D5A3]">
            <span class="text-4xl" id="helperIcon">🦁</span>
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Let's learn numbers from 10 to 19!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 4: Numbers 10-19 (Teen Numbers)
    // 20 Slides: True/False and Matching Games
    // Learning: 10 + 1 through 10 + 9
    // ============================================

    let currentSlide = 0;
    let canProceed = false;
    let matchingPairs = [];
    let selectedCards = [];
    
    const helperMessages = {
        truefalse: "Read carefully! Does 10 plus this number equal the answer?",
        matching: "Match each equation with the correct answer! Click on an equation, then click on its answer.",
        correct: "🎉 Great job! You got it right!",
        wrong: "😅 Oops! That's not correct. Try again!",
        complete: "🌟 Perfect match! Keep going!",
        matchComplete: "🎊 Amazing! You completed the matching game!"
    };
    
    // Lesson 4 Slides - Numbers 10-19 (10 + 1 through 10 + 9)
    const slides = [
        // Slide 1 - Introduction to teen numbers
        { type: "truefalse", text: "10 + 1 = 11", correct: true, explanation: "10 + 1 = 11 is correct! 11 is ten plus one!" },
        // Slide 2 - True/False
        { type: "truefalse", text: "10 + 2 = 13", correct: false, explanation: "10 + 2 = 12, not 13. So this is false!" },
        // Slide 3 - Matching Game 1 (Basic teen numbers)
        { type: "matching", pairs: [
            { equation: "10 + 1", answer: 11 },
            { equation: "10 + 2", answer: 12 },
            { equation: "10 + 3", answer: 13 },
            { equation: "10 + 4", answer: 14 }
        ]},
        // Slide 4 - True/False
        { type: "truefalse", text: "10 + 3 = 13", correct: true, explanation: "10 + 3 = 13 is correct! 13 is ten plus three!" },
        // Slide 5 - True/False
        { type: "truefalse", text: "10 + 5 = 16", correct: false, explanation: "10 + 5 = 15, not 16. So this is false!" },
        // Slide 6 - Matching Game 2 (More teen numbers)
        { type: "matching", pairs: [
            { equation: "10 + 5", answer: 15 },
            { equation: "10 + 6", answer: 16 },
            { equation: "10 + 7", answer: 17 },
            { equation: "10 + 8", answer: 18 }
        ]},
        // Slide 7 - True/False
        { type: "truefalse", text: "10 + 6 = 16", correct: true, explanation: "10 + 6 = 16 is correct! 16 is ten plus six!" },
        // Slide 8 - True/False
        { type: "truefalse", text: "10 + 8 = 19", correct: false, explanation: "10 + 8 = 18, not 19. So this is false!" },
        // Slide 9 - Matching Game 3 (Mixed teen numbers)
        { type: "matching", pairs: [
            { equation: "10 + 9", answer: 19 },
            { equation: "10 + 4", answer: 14 },
            { equation: "10 + 7", answer: 17 },
            { equation: "10 + 2", answer: 12 }
        ]},
        // Slide 10 - True/False
        { type: "truefalse", text: "10 + 9 = 19", correct: true, explanation: "10 + 9 = 19 is correct! 19 is ten plus nine!" },
        // Slide 11 - True/False
        { type: "truefalse", text: "10 + 4 = 15", correct: false, explanation: "10 + 4 = 14, not 15. So this is false!" },
        // Slide 12 - Matching Game 4 (All teen numbers mixed)
        { type: "matching", pairs: [
            { equation: "10 + 3", answer: 13 },
            { equation: "10 + 6", answer: 16 },
            { equation: "10 + 1", answer: 11 },
            { equation: "10 + 8", answer: 18 }
        ]},
        // Slide 13 - True/False
        { type: "truefalse", text: "10 + 7 = 17", correct: true, explanation: "10 + 7 = 17 is correct! Keep going!" },
        // Slide 14 - True/False
        { type: "truefalse", text: "10 + 2 = 11", correct: false, explanation: "10 + 2 = 12, not 11. So this is false!" },
        // Slide 15 - Matching Game 5 (Challenge - reverse matching)
        { type: "matching", pairs: [
            { equation: "10 + ⬜ = 15", missing: 5 },
            { equation: "10 + ⬜ = 17", missing: 7 },
            { equation: "10 + ⬜ = 12", missing: 2 },
            { equation: "10 + ⬜ = 18", missing: 8 }
        ]},
        // Slide 16 - True/False
        { type: "truefalse", text: "10 + 5 = 15", correct: true, explanation: "10 + 5 = 15 is correct! You're doing great!" },
        // Slide 17 - True/False
        { type: "truefalse", text: "10 + 1 = 10", correct: false, explanation: "10 + 1 = 11, not 10. So this is false!" },
        // Slide 18 - Matching Game 6 (Final review - all types)
        { type: "matching", pairs: [
            { equation: "10 + 4", answer: 14 },
            { equation: "10 + 9", answer: 19 },
            { equation: "10 + 2", answer: 12 },
            { equation: "10 + 7", answer: 17 }
        ]},
        // Slide 19 - True/False
        { type: "truefalse", text: "10 + 8 = 18", correct: true, explanation: "10 + 8 = 18 is correct! Almost there!" },
        // Slide 20 - True/False (Final)
        { type: "truefalse", text: "10 + 6 = 15", correct: false, explanation: "10 + 6 = 16, not 15. But you've completed Lesson 4! Amazing! Now you know numbers 10 to 19!" }
    ];

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, rendering first slide');
        renderSlide();
    });

    function updateProgress() {
        const percent = ((currentSlide + 1) / slides.length) * 100;
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        if (progressBar) progressBar.style.width = `${percent}%`;
        if (progressText) progressText.innerHTML = `Slide ${currentSlide + 1}/${slides.length}`;
    }

    function renderSlide() {
        console.log('Rendering slide:', currentSlide);
        const slide = slides[currentSlide];
        const contentArea = document.getElementById('contentArea');
        
        if (!contentArea) {
            console.error('Content area not found!');
            return;
        }
        
        canProceed = false;
        
        if (slide.type === "truefalse") {
            renderTrueFalse(slide, contentArea);
        } else if (slide.type === "matching") {
            renderMatching(slide, contentArea);
        }
        
        updateProgress();
        
        const helperMessageEl = document.getElementById('helperMessage');
        const helperIconEl = document.getElementById('helperIcon');
        if (helperMessageEl) helperMessageEl.innerHTML = `"${helperMessages[slide.type]}"`;
        if (helperIconEl) helperIconEl.innerHTML = slide.type === "truefalse" ? "🦁" : "🐯";
        
        const nextBtn = document.getElementById('nextBtn');
        if (nextBtn) {
            if (!canProceed) {
                nextBtn.classList.add('opacity-50');
            } else {
                nextBtn.classList.remove('opacity-50');
            }
        }
    }
    
    function renderTrueFalse(slide, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-12 mb-8">
                    <div class="text-6xl md:text-7xl font-bold text-[#5A6B2D] mb-8">
                        ${slide.text}
                    </div>
                    <div class="flex justify-center gap-8">
                        <button onclick="checkTrueFalse(true)" class="true-btn bg-green-500 hover:bg-green-600 text-white text-3xl font-bold px-12 py-6 rounded-2xl transition-all hover:scale-105 shadow-lg">
                            ✓ TRUE
                        </button>
                        <button onclick="checkTrueFalse(false)" class="false-btn bg-red-500 hover:bg-red-600 text-white text-3xl font-bold px-12 py-6 rounded-2xl transition-all hover:scale-105 shadow-lg">
                            ✗ FALSE
                        </button>
                    </div>
                </div>
                <div id="tf-feedback" class="text-xl min-h-[80px]"></div>
            </div>
        `;
    }
    
    function checkTrueFalse(answer) {
        const slide = slides[currentSlide];
        const feedbackDiv = document.getElementById('tf-feedback');
        const trueBtn = document.querySelector('.true-btn');
        const falseBtn = document.querySelector('.false-btn');
        
        if (trueBtn) trueBtn.disabled = true;
        if (falseBtn) falseBtn.disabled = true;
        
        if (answer === slide.correct) {
            feedbackDiv.innerHTML = `<span class="text-green-600 text-2xl">✅ ${slide.explanation}</span>`;
            canProceed = true;
            document.getElementById('nextBtn').classList.remove('opacity-50');
            document.getElementById('helperMessage').innerHTML = `"${helperMessages.correct}"`;
        } else {
            feedbackDiv.innerHTML = `<span class="text-red-600 text-2xl">❌ ${slide.explanation}</span>`;
            setTimeout(() => {
                if (trueBtn) { trueBtn.disabled = false; }
                if (falseBtn) { falseBtn.disabled = false; }
                feedbackDiv.innerHTML = '';
                document.getElementById('helperMessage').innerHTML = `"${helperMessages.wrong} Try again!"`;
            }, 2000);
        }
    }
    
    function renderMatching(slide, contentArea) {
        matchingPairs = [...slide.pairs];
        selectedCards = [];
        
        // Shuffle both sides separately
        const shuffledEquations = [...matchingPairs].sort(() => Math.random() - 0.5);
        const shuffledAnswers = [...matchingPairs].sort(() => Math.random() - 0.5);
        
        contentArea.innerHTML = `
            <div class="text-center mb-4">
                <p class="text-[#7A8B3B] text-lg">Match each equation with the correct answer!</p>
                <p class="text-[#8C9DB3] text-sm mt-1">Click on an equation, then click on its answer</p>
            </div>
            <div class="grid grid-cols-2 gap-8">
                <!-- Equations Column -->
                <div class="bg-[#E8F0E0] rounded-2xl p-6">
                    <h3 class="text-center text-2xl font-bold text-[#5A6B2D] mb-4">📝 Equations</h3>
                    <div id="equations-list" class="space-y-3">
                        ${shuffledEquations.map((pair, idx) => `
                            <div class="equation-card bg-white rounded-xl p-4 text-center cursor-pointer transition-all hover:scale-105 border-2 border-[#D0E0B8]" data-equation="${pair.equation}" data-value="${pair.answer || pair.missing}" data-matched="false" data-type="${pair.answer ? 'normal' : 'missing'}">
                                <span class="text-2xl font-bold text-[#5A6B2D]">${pair.equation}</span>
                                ${!pair.answer ? '<span class="text-xl text-[#8C9DB3] ml-2"></span>' : ''}
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <!-- Answers Column -->
                <div class="bg-[#E8F0E0] rounded-2xl p-6">
                    <h3 class="text-center text-2xl font-bold text-[#5A6B2D] mb-4">🎯 Answers</h3>
                    <div id="answers-list" class="space-y-3">
                        ${shuffledAnswers.map((pair, idx) => `
                            <div class="answer-card bg-white rounded-xl p-4 text-center cursor-pointer transition-all hover:scale-105 border-2 border-[#D0E0B8]" data-answer="${pair.answer || pair.missing}" data-matched="false">
                                <span class="text-3xl font-bold text-[#7A8B3B]">${pair.answer || pair.missing}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
            <div id="matching-feedback" class="text-center mt-6 text-xl min-h-[60px]"></div>
        `;
        
        document.querySelectorAll('.equation-card').forEach(card => {
            card.addEventListener('click', () => selectEquation(card));
        });
        
        document.querySelectorAll('.answer-card').forEach(card => {
            card.addEventListener('click', () => selectAnswer(card));
        });
    }
    
    function selectEquation(card) {
        if (card.dataset.matched === 'true') return;
        
        document.querySelectorAll('.equation-card').forEach(eq => {
            eq.classList.remove('border-[#F4A623]', 'bg-[#F0E8D0]');
            eq.classList.add('border-[#D0E0B8]');
        });
        
        card.classList.add('border-[#F4A623]', 'bg-[#F0E8D0]');
        card.classList.remove('border-[#D0E0B8]');
        selectedCards = [card, null];
        document.getElementById('matching-feedback').innerHTML = '';
    }
    
    function selectAnswer(card) {
        if (card.dataset.matched === 'true') return;
        
        if (!selectedCards[0]) {
            document.getElementById('matching-feedback').innerHTML = '<span class="text-[#E8A317]">First click on an equation!</span>';
            setTimeout(() => {
                document.getElementById('matching-feedback').innerHTML = '';
            }, 1500);
            return;
        }
        
        const equationCard = selectedCards[0];
        const requiredValue = parseInt(equationCard.dataset.value);
        const selectedValue = parseInt(card.dataset.answer);
        
        if (requiredValue === selectedValue) {
            equationCard.dataset.matched = 'true';
            card.dataset.matched = 'true';
            equationCard.classList.add('opacity-50', 'bg-green-100');
            card.classList.add('opacity-50', 'bg-green-100');
            equationCard.style.cursor = 'default';
            card.style.cursor = 'default';
            
            document.getElementById('matching-feedback').innerHTML = '<span class="text-green-600">✅ Perfect match!</span>';
            selectedCards = [];
            
            const allEquations = document.querySelectorAll('.equation-card');
            const allMatched = Array.from(allEquations).every(eq => eq.dataset.matched === 'true');
            
            if (allMatched) {
                document.getElementById('matching-feedback').innerHTML = `<span class="text-green-600 text-2xl">🎉 ${helperMessages.matchComplete} 🎉</span>`;
                canProceed = true;
                document.getElementById('nextBtn').classList.remove('opacity-50');
                document.getElementById('helperMessage').innerHTML = `"${helperMessages.complete}"`;
            }
        } else {
            document.getElementById('matching-feedback').innerHTML = '<span class="text-red-600">❌ Not a match! Try again!</span>';
            selectedCards = [];
            
            equationCard.classList.remove('border-[#F4A623]', 'bg-[#F0E8D0]');
            equationCard.classList.add('border-[#D0E0B8]');
            
            setTimeout(() => {
                document.getElementById('matching-feedback').innerHTML = '';
            }, 1500);
        }
    }
    
    function nextSlide() {
        if (!canProceed && slides[currentSlide].type !== "matching") {
            document.getElementById('helperMessage').innerHTML = `"${helperMessages.wrong} Answer the question first!"`;
            return;
        }
        
        if (currentSlide < slides.length - 1) {
            currentSlide++;
            canProceed = false;
            renderSlide();
            
            if (currentSlide > 0) {
                document.getElementById('prevBtn').classList.remove('hidden');
            }
            
            if (currentSlide === slides.length - 1) {
                document.getElementById('nextBtn').innerHTML = 'Finish Lesson 🎉';
            } else {
                document.getElementById('nextBtn').innerHTML = 'Next →';
            }
        } else {
            completeLesson();
        }
    }
    
    function previousSlide() {
        if (currentSlide > 0) {
            currentSlide--;
            canProceed = true;
            renderSlide();
            
            if (currentSlide === 0) {
                document.getElementById('prevBtn').classList.add('hidden');
            }
            
            if (currentSlide < slides.length - 1) {
                document.getElementById('nextBtn').innerHTML = 'Next →';
            }
        }
    }
    
    // Save to database function
    async function saveLessonCompletion() {
        console.log('Saving lesson completion to database...');
        try {
            const response = await fetch('/api/lesson-complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    lesson_number: 4
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 4 saved to database successfully!');
            } else {
                console.error('❌ Failed to save lesson:', data);
            }
            return data;
        } catch (error) {
            console.error('Error saving lesson:', error);
            throw error;
        }
    }
    
    function completeLesson() {
        console.log('Completing lesson...');
        // Save to database first
        saveLessonCompletion().then(() => {
            // Save to localStorage as backup
            try {
                const savedProgress = localStorage.getItem('lessonProgress');
                let progress = savedProgress ? JSON.parse(savedProgress) : { completedLessons: [] };
                if (!progress.completedLessons.includes(4)) {
                    progress.completedLessons.push(4);
                    localStorage.setItem('lessonProgress', JSON.stringify(progress));
                }
            } catch(e) {
                console.log("Progress save error:", e);
            }
            
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-8xl mb-4">🎉🏆🎉</div>
                    <h2 class="text-4xl font-bold text-[#7A8B3B] mb-4">CONGRATULATIONS!</h2>
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 4: Numbers 10-19!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you know all the teen numbers from 11 to 19!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('prevBtn').classList.add('hidden');
            document.getElementById('helperMessage').innerHTML = '"Amazing work! You\'re a teen number expert! 🌟"';
            document.getElementById('helperIcon').innerHTML = "🌟";
        }).catch(error => {
            console.error('Error saving to database:', error);
            // Still show completion even if database fails
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-8xl mb-4">🎉🏆🎉</div>
                    <h2 class="text-4xl font-bold text-[#7A8B3B] mb-4">CONGRATULATIONS!</h2>
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 4: Numbers 10-19!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you know all the teen numbers from 11 to 19!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('prevBtn').classList.add('hidden');
            document.getElementById('helperMessage').innerHTML = '"Amazing work! You\'re a teen number expert! 🌟"';
            document.getElementById('helperIcon').innerHTML = "🌟";
        });
    }
</script>
@endsection