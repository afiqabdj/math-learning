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
                    <span class="text-3xl">➖</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 5: Subtraction (Numbers below 10)</span>
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
            <span class="text-4xl" id="helperIcon">🐻</span>
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Let's learn subtraction! Take away numbers to make smaller numbers!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 5: Subtraction (Numbers below 10)
    // 20 Slides: True/False and Matching Games
    // Learning: Subtraction facts from 1-9
    // ============================================

    let currentSlide = 0;
    let canProceed = false;
    let matchingPairs = [];
    let selectedCards = [];
    
    const helperMessages = {
        truefalse: "Read carefully! Is this subtraction equation correct?",
        matching: "Match each subtraction equation with the correct answer! Click on an equation, then click on its answer.",
        correct: "🎉 Great job! You got it right!",
        wrong: "😅 Oops! That's not correct. Try again!",
        complete: "🌟 Perfect match! Keep going!",
        matchComplete: "🎊 Amazing! You completed the matching game!"
    };
    
    // Lesson 5 Slides - Subtraction with numbers below 10
    const slides = [
        // Slide 1 - Introduction to subtraction
        { type: "truefalse", text: "5 - 2 = 3", correct: true, explanation: "5 - 2 = 3 is correct! Take away 2 from 5, you get 3!" },
        // Slide 2 - True/False
        { type: "truefalse", text: "4 - 1 = 2", correct: false, explanation: "4 - 1 = 3, not 2. So this is false!" },
        // Slide 3 - Matching Game 1 (Basic subtraction 1-5)
        { type: "matching", pairs: [
            { equation: "3 - 1", answer: 2 },
            { equation: "4 - 2", answer: 2 },
            { equation: "5 - 3", answer: 2 },
            { equation: "6 - 4", answer: 2 }
        ]},
        // Slide 4 - True/False
        { type: "truefalse", text: "6 - 3 = 3", correct: true, explanation: "6 - 3 = 3 is correct! 6 take away 3 equals 3!" },
        // Slide 5 - True/False
        { type: "truefalse", text: "7 - 2 = 4", correct: false, explanation: "7 - 2 = 5, not 4. So this is false!" },
        // Slide 6 - Matching Game 2 (Subtraction facts 1-5)
        { type: "matching", pairs: [
            { equation: "5 - 1", answer: 4 },
            { equation: "6 - 2", answer: 4 },
            { equation: "7 - 3", answer: 4 },
            { equation: "8 - 4", answer: 4 }
        ]},
        // Slide 7 - True/False
        { type: "truefalse", text: "8 - 4 = 4", correct: true, explanation: "8 - 4 = 4 is correct! 8 take away 4 equals 4!" },
        // Slide 8 - True/False
        { type: "truefalse", text: "9 - 5 = 5", correct: false, explanation: "9 - 5 = 4, not 5. So this is false!" },
        // Slide 9 - Matching Game 3 (Subtraction to get 3)
        { type: "matching", pairs: [
            { equation: "4 - 1", answer: 3 },
            { equation: "5 - 2", answer: 3 },
            { equation: "6 - 3", answer: 3 },
            { equation: "7 - 4", answer: 3 }
        ]},
        // Slide 10 - True/False
        { type: "truefalse", text: "7 - 3 = 4", correct: true, explanation: "7 - 3 = 4 is correct! You're doing great!" },
        // Slide 11 - True/False
        { type: "truefalse", text: "9 - 2 = 6", correct: false, explanation: "9 - 2 = 7, not 6. So this is false!" },
        // Slide 12 - Matching Game 4 (Subtraction to get 5)
        { type: "matching", pairs: [
            { equation: "9 - 4", answer: 5 },
            { equation: "8 - 3", answer: 5 },
            { equation: "7 - 2", answer: 5 },
            { equation: "6 - 1", answer: 5 }
        ]},
        // Slide 13 - True/False
        { type: "truefalse", text: "8 - 2 = 6", correct: true, explanation: "8 - 2 = 6 is correct! Keep going!" },
        // Slide 14 - True/False
        { type: "truefalse", text: "6 - 4 = 3", correct: false, explanation: "6 - 4 = 2, not 3. So this is false!" },
        // Slide 15 - Matching Game 5 (Subtraction with missing number)
        { type: "matching", pairs: [
            { equation: "9 - ⬜ = 4", missing: 5 },
            { equation: "8 - ⬜ = 3", missing: 5 },
            { equation: "7 - ⬜ = 2", missing: 5 },
            { equation: "6 - ⬜ = 1", missing: 5 }
        ]},
        // Slide 16 - True/False
        { type: "truefalse", text: "9 - 6 = 3", correct: true, explanation: "9 - 6 = 3 is correct! You're getting good at subtraction!" },
        // Slide 17 - True/False
        { type: "truefalse", text: "5 - 3 = 1", correct: false, explanation: "5 - 3 = 2, not 1. So this is false!" },
        // Slide 18 - Matching Game 6 (Final review - mixed subtraction)
        { type: "matching", pairs: [
            { equation: "7 - 5", answer: 2 },
            { equation: "9 - 7", answer: 2 },
            { equation: "8 - 6", answer: 2 },
            { equation: "6 - 4", answer: 2 }
        ]},
        // Slide 19 - True/False
        { type: "truefalse", text: "9 - 8 = 1", correct: true, explanation: "9 - 8 = 1 is correct! Almost there!" },
        // Slide 20 - True/False (Final)
        { type: "truefalse", text: "8 - 5 = 2", correct: false, explanation: "8 - 5 = 3, not 2. But you've completed Lesson 5! Amazing! Now you know subtraction with numbers below 10!" }
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
        if (helperIconEl) helperIconEl.innerHTML = slide.type === "truefalse" ? "🐻" : "🐼";
        
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
                <p class="text-[#7A8B3B] text-lg">Match each subtraction equation with the correct answer!</p>
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
                    lesson_number: 5
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 5 saved to database successfully!');
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
                if (!progress.completedLessons.includes(5)) {
                    progress.completedLessons.push(5);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 5: Subtraction!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you know how to subtract numbers below 10!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('prevBtn').classList.add('hidden');
            document.getElementById('helperMessage').innerHTML = '"Amazing work! You\'re a subtraction superstar! 🌟"';
            document.getElementById('helperIcon').innerHTML = "🌟";
        }).catch(error => {
            console.error('Error saving to database:', error);
            // Still show completion even if database fails
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-8xl mb-4">🎉🏆🎉</div>
                    <h2 class="text-4xl font-bold text-[#7A8B3B] mb-4">CONGRATULATIONS!</h2>
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 5: Subtraction!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you know how to subtract numbers below 10!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('prevBtn').classList.add('hidden');
            document.getElementById('helperMessage').innerHTML = '"Amazing work! You\'re a subtraction superstar! 🌟"';
            document.getElementById('helperIcon').innerHTML = "🌟";
        });
    }
</script>
@endsection