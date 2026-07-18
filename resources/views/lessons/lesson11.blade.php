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
                    <span class="text-3xl">🧮</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 11: Subtracting Two-Digit Numbers (Borrowing)</span>
                </div>
            </div>

            <div class="px-8 mb-4">
                <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                    <span>Lesson Progress</span>
                    <span id="progressText">Step 1/32</span>
                </div>
                <div class="bg-[#E8D5A3] rounded-full h-3 overflow-hidden">
                    <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-500" style="width: 3%"></div>
                </div>
            </div>

            <div class="p-8 min-h-[550px]">
                <div id="contentArea"></div>
            </div>

            <div class="flex justify-between px-8 pb-8">
                <button id="prevBtn" onclick="previousStep()" class="bg-[#8C9DB3] hover:bg-[#7D8FA8] text-white px-6 py-2 rounded-full font-bold transition-all hover:scale-105 shadow-md hidden">
                    ← Previous
                </button>
                <button id="nextBtn" onclick="nextStep()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-6 py-2 rounded-full font-bold transition-all hover:scale-105 shadow-md ml-auto">
                    Next →
                </button>
            </div>

            <div class="h-2 bg-gradient-to-r from-[#E8D5A3] via-[#F4A623] to-[#E8D5A3]"></div>
        </div>
    </div>

    <div class="text-center mt-8">
        <div class="inline-flex items-center space-x-3 bg-[#FDF8F0]/90 rounded-full px-6 py-3 shadow-lg border border-[#E8D5A3]">
            <span class="text-4xl" id="helperIcon">🦸</span>
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Learn how to subtract when the ones digit is smaller!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 11: Subtracting Two-Digit Numbers (Borrowing)
    // Technique: Round down to tens, subtract, then add back the ones
    // Formula: (num1 tens - num2 tens - 1) + (num1 ones + (10 - num2 ones))
    // Format: Step-by-step demonstration + MCQ
    // ============================================

    let currentStep = 0;
    let canProceed = false;
    
    // Lesson steps (32 total - 8 examples with 4 steps each + 8 MCQ)
    const steps = [
        // Example 1: 23 - 19
        { type: "example", num1: 23, num2: 19, explanation: "Let's learn how to subtract 23 - 19 using a simple trick!" },
        { type: "step1", num1: 23, num2: 19, tens1: 20, ones1: 3, tens2: 10, ones2: 9, text: "Step 1: Round down to tens (20 - 10)", hint: "23 ≈ 20, 19 ≈ 10" },
        { type: "step2", tens1: 20, tens2: 10, tensResult: 10, ones1: 3, ones2: 9, text: "Step 2: Subtract tens (20 - 10 = 10)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 10, ones1: 3, ones2: 9, remaining: 10, stepResult: 4, text: "Step 3: Subtract ones (10 - 9 = 1), then add the first ones (1 + 3 = 4)", hint: "10 - 9 = 1, then 1 + 3 = 4" },
        { type: "step4", result: 4, text: "Step 4: That's your answer!", hint: "23 - 19 = 4" },
        { type: "answer", num1: 23, num2: 19, result: 4, text: "So 23 - 19 = 4! 🎉" },
        
        // MCQ 1
        { type: "mcq", num1: 34, num2: 18, result: 16, question: "34 - 18 = ? Round down: 30 - 10 = 20, then 20 - 8 = 12, then 12 + 4 = __", correct: "16", options: ["14", "15", "16", "17"] },
        
        // Example 2: 56 - 19
        { type: "example", num1: 56, num2: 19, explanation: "Now let's try 56 - 19!" },
        { type: "step1", num1: 56, num2: 19, tens1: 50, ones1: 6, tens2: 10, ones2: 9, text: "Step 1: Round down to tens (50 - 10)", hint: "56 ≈ 50, 19 ≈ 10" },
        { type: "step2", tens1: 50, tens2: 10, tensResult: 40, ones1: 6, ones2: 9, text: "Step 2: Subtract tens (50 - 10 = 40)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 40, ones1: 6, ones2: 9, remaining: 40, stepResult: 37, text: "Step 3: Subtract ones (40 - 9 = 31), then add the first ones (31 + 6 = 37)", hint: "40 - 9 = 31, then 31 + 6 = 37" },
        { type: "step4", result: 37, text: "Step 4: That's your answer!", hint: "56 - 19 = 37" },
        { type: "answer", num1: 56, num2: 19, result: 37, text: "So 56 - 19 = 37! 🎉" },
        
        // MCQ 2
        { type: "mcq", num1: 45, num2: 18, result: 27, question: "45 - 18 = ? Round down: 40 - 10 = 30, then 30 - 8 = 22, then 22 + 5 = __", correct: "27", options: ["25", "26", "27", "28"] },
        
        // Example 3: 62 - 28
        { type: "example", num1: 62, num2: 28, explanation: "Let's practice 62 - 28!" },
        { type: "step1", num1: 62, num2: 28, tens1: 60, ones1: 2, tens2: 20, ones2: 8, text: "Step 1: Round down to tens (60 - 20)", hint: "62 ≈ 60, 28 ≈ 20" },
        { type: "step2", tens1: 60, tens2: 20, tensResult: 40, ones1: 2, ones2: 8, text: "Step 2: Subtract tens (60 - 20 = 40)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 40, ones1: 2, ones2: 8, remaining: 40, stepResult: 34, text: "Step 3: Subtract ones (40 - 8 = 32), then add the first ones (32 + 2 = 34)", hint: "40 - 8 = 32, then 32 + 2 = 34" },
        { type: "step4", result: 34, text: "Step 4: That's your answer!", hint: "62 - 28 = 34" },
        { type: "answer", num1: 62, num2: 28, result: 34, text: "So 62 - 28 = 34! 🎉" },
        
        // MCQ 3
        { type: "mcq", num1: 53, num2: 27, result: 26, question: "53 - 27 = ? Round down: 50 - 20 = 30, then 30 - 7 = 23, then 23 + 3 = __", correct: "26", options: ["24", "25", "26", "27"] },
        
        // Example 4: 71 - 39
        { type: "example", num1: 71, num2: 39, explanation: "Now a bigger one! 71 - 39" },
        { type: "step1", num1: 71, num2: 39, tens1: 70, ones1: 1, tens2: 30, ones2: 9, text: "Step 1: Round down to tens (70 - 30)", hint: "71 ≈ 70, 39 ≈ 30" },
        { type: "step2", tens1: 70, tens2: 30, tensResult: 40, ones1: 1, ones2: 9, text: "Step 2: Subtract tens (70 - 30 = 40)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 40, ones1: 1, ones2: 9, remaining: 40, stepResult: 32, text: "Step 3: Subtract ones (40 - 9 = 31), then add the first ones (31 + 1 = 32)", hint: "40 - 9 = 31, then 31 + 1 = 32" },
        { type: "step4", result: 32, text: "Step 4: That's your answer!", hint: "71 - 39 = 32" },
        { type: "answer", num1: 71, num2: 39, result: 32, text: "So 71 - 39 = 32! 🎉" },
        
        // MCQ 4
        { type: "mcq", num1: 84, num2: 37, result: 47, question: "84 - 37 = ? Round down: 80 - 30 = 50, then 50 - 7 = 43, then 43 + 4 = __", correct: "47", options: ["45", "46", "47", "48"] },
        
        // Example 5: 38 - 19
        { type: "example", num1: 38, num2: 19, explanation: "Let's try 38 - 19!" },
        { type: "step1", num1: 38, num2: 19, tens1: 30, ones1: 8, tens2: 10, ones2: 9, text: "Step 1: Round down to tens (30 - 10)", hint: "38 ≈ 30, 19 ≈ 10" },
        { type: "step2", tens1: 30, tens2: 10, tensResult: 20, ones1: 8, ones2: 9, text: "Step 2: Subtract tens (30 - 10 = 20)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 20, ones1: 8, ones2: 9, remaining: 20, stepResult: 19, text: "Step 3: Subtract ones (20 - 9 = 11), then add the first ones (11 + 8 = 19)", hint: "20 - 9 = 11, then 11 + 8 = 19" },
        { type: "step4", result: 19, text: "Step 4: That's your answer!", hint: "38 - 19 = 19" },
        { type: "answer", num1: 38, num2: 19, result: 19, text: "So 38 - 19 = 19! 🎉" },
        
        // MCQ 5
        { type: "mcq", num1: 47, num2: 29, result: 18, question: "47 - 29 = ? Round down: 40 - 20 = 20, then 20 - 9 = 11, then 11 + 7 = __", correct: "18", options: ["16", "17", "18", "19"] },
        
        // Example 6: 83 - 47
        { type: "example", num1: 83, num2: 47, explanation: "Let's practice 83 - 47!" },
        { type: "step1", num1: 83, num2: 47, tens1: 80, ones1: 3, tens2: 40, ones2: 7, text: "Step 1: Round down to tens (80 - 40)", hint: "83 ≈ 80, 47 ≈ 40" },
        { type: "step2", tens1: 80, tens2: 40, tensResult: 40, ones1: 3, ones2: 7, text: "Step 2: Subtract tens (80 - 40 = 40)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 40, ones1: 3, ones2: 7, remaining: 40, stepResult: 36, text: "Step 3: Subtract ones (40 - 7 = 33), then add the first ones (33 + 3 = 36)", hint: "40 - 7 = 33, then 33 + 3 = 36" },
        { type: "step4", result: 36, text: "Step 4: That's your answer!", hint: "83 - 47 = 36" },
        { type: "answer", num1: 83, num2: 47, result: 36, text: "So 83 - 47 = 36! 🎉" },
        
        // MCQ 6
        { type: "mcq", num1: 92, num2: 48, result: 44, question: "92 - 48 = ? Round down: 90 - 40 = 50, then 50 - 8 = 42, then 42 + 2 = __", correct: "44", options: ["42", "43", "44", "45"] },
        
        // Example 7: 75 - 39
        { type: "example", num1: 75, num2: 39, explanation: "One more! 75 - 39" },
        { type: "step1", num1: 75, num2: 39, tens1: 70, ones1: 5, tens2: 30, ones2: 9, text: "Step 1: Round down to tens (70 - 30)", hint: "75 ≈ 70, 39 ≈ 30" },
        { type: "step2", tens1: 70, tens2: 30, tensResult: 40, ones1: 5, ones2: 9, text: "Step 2: Subtract tens (70 - 30 = 40)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 40, ones1: 5, ones2: 9, remaining: 40, stepResult: 36, text: "Step 3: Subtract ones (40 - 9 = 31), then add the first ones (31 + 5 = 36)", hint: "40 - 9 = 31, then 31 + 5 = 36" },
        { type: "step4", result: 36, text: "Step 4: That's your answer!", hint: "75 - 39 = 36" },
        { type: "answer", num1: 75, num2: 39, result: 36, text: "So 75 - 39 = 36! 🎉" },
        
        // MCQ 7
        { type: "mcq", num1: 66, num2: 38, result: 28, question: "66 - 38 = ? Round down: 60 - 30 = 30, then 30 - 8 = 22, then 22 + 6 = __", correct: "28", options: ["26", "27", "28", "29"] },
        
        // Example 8: 94 - 57
        { type: "example", num1: 94, num2: 57, explanation: "Last example! 94 - 57" },
        { type: "step1", num1: 94, num2: 57, tens1: 90, ones1: 4, tens2: 50, ones2: 7, text: "Step 1: Round down to tens (90 - 50)", hint: "94 ≈ 90, 57 ≈ 50" },
        { type: "step2", tens1: 90, tens2: 50, tensResult: 40, ones1: 4, ones2: 7, text: "Step 2: Subtract tens (90 - 50 = 40)", hint: "Then we need to handle the ones" },
        { type: "step3", tensResult: 40, ones1: 4, ones2: 7, remaining: 40, stepResult: 37, text: "Step 3: Subtract ones (40 - 7 = 33), then add the first ones (33 + 4 = 37)", hint: "40 - 7 = 33, then 33 + 4 = 37" },
        { type: "step4", result: 37, text: "Step 4: That's your answer!", hint: "94 - 57 = 37" },
        { type: "answer", num1: 94, num2: 57, result: 37, text: "So 94 - 57 = 37! 🎉" },
        
        // Final MCQ 8
        { type: "mcq", num1: 81, num2: 49, result: 32, question: "81 - 49 = ? Round down: 80 - 40 = 40, then 40 - 9 = 31, then 31 + 1 = __", correct: "32", options: ["30", "31", "32", "33"] },
        
        // Final Practice
        { type: "practice", text: "Great job! You've learned how to subtract two-digit numbers with borrowing! Remember: round down to tens, subtract, then handle the ones!" }
    ];

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, rendering first step');
        renderStep();
    });

    function updateProgress() {
        const percent = ((currentStep + 1) / steps.length) * 100;
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        if (progressBar) progressBar.style.width = `${percent}%`;
        if (progressText) progressText.innerHTML = `Step ${currentStep + 1}/${steps.length}`;
    }

    function renderStep() {
        console.log('Rendering step:', currentStep);
        const step = steps[currentStep];
        const contentArea = document.getElementById('contentArea');
        
        if (!contentArea) {
            console.error('Content area not found!');
            return;
        }
        
        canProceed = false;
        
        if (step.type === "example") {
            renderExample(step, contentArea);
        } else if (step.type === "step1") {
            renderStep1(step, contentArea);
        } else if (step.type === "step2") {
            renderStep2(step, contentArea);
        } else if (step.type === "step3") {
            renderStep3(step, contentArea);
        } else if (step.type === "step4") {
            renderStep4(step, contentArea);
        } else if (step.type === "answer") {
            renderAnswer(step, contentArea);
        } else if (step.type === "mcq") {
            renderMCQ(step, contentArea);
        } else if (step.type === "practice") {
            renderPractice(step, contentArea);
        }
        
        updateProgress();
        
        // Set helper message based on step type
        let message = "";
        if (step.type === "example") message = "Let's learn a new trick! 🎯";
        else if (step.type === "step1") message = "Step 1: Round down to tens";
        else if (step.type === "step2") message = "Step 2: Subtract the tens";
        else if (step.type === "step3") message = "Step 3: Handle the ones";
        else if (step.type === "step4") message = "Step 4: That's your answer!";
        else if (step.type === "answer") message = "Great job! 🎉";
        else if (step.type === "mcq") message = "Choose the correct answer!";
        else if (step.type === "practice") message = "You've got this! 🌟";
        
        const helperMessageEl = document.getElementById('helperMessage');
        if (helperMessageEl) helperMessageEl.innerHTML = message;
        
        const nextBtn = document.getElementById('nextBtn');
        if (nextBtn) {
            if (!canProceed) {
                nextBtn.classList.add('opacity-50');
            } else {
                nextBtn.classList.remove('opacity-50');
            }
        }
        
        const prevBtn = document.getElementById('prevBtn');
        if (prevBtn) {
            if (currentStep === 0) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }
        }
    }
    
    function renderExample(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-5xl md:text-6xl font-bold text-[#5A6B2D] mb-4">
                        ${step.num1} - ${step.num2} = ?
                    </div>
                    <p class="text-xl text-[#7A8B3B]">${step.explanation}</p>
                </div>
                <button onclick="proceedToNext()" class="bg-[#F4A623] hover:bg-[#E8A317] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md mt-4">
                    Let's Learn the Trick! 🎯
                </button>
            </div>
        `;
        canProceed = false;
        window.proceedToNext = () => {
            currentStep++;
            renderStep();
        };
    }
    
    function renderStep1(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl text-[#5A6B2D] mb-4">${step.text}</div>
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">-</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second tens</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 <span class="font-bold">${step.hint}</span></p>
                    </div>
                </div>
                <button onclick="proceedToNext()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                    Next Step →
                </button>
            </div>
        `;
        canProceed = false;
        window.proceedToNext = () => {
            currentStep++;
            renderStep();
        };
    }
    
    function renderStep2(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl text-[#5A6B2D] mb-4">${step.text}</div>
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">-</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tensResult}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Tens result</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 ${step.hint}</p>
                    </div>
                </div>
                <button onclick="proceedToNext()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                    Next Step →
                </button>
            </div>
        `;
        canProceed = false;
        window.proceedToNext = () => {
            currentStep++;
            renderStep();
        };
    }
    
    function renderStep3(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl text-[#5A6B2D] mb-4">${step.text}</div>
                    <div class="flex justify-center items-center space-x-8 my-6 flex-wrap">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tensResult}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Tens result</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">-</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.ones2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second ones</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tensResult - step.ones2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">After subtraction</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.ones1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First ones</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.stepResult}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Final answer</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 ${step.hint}</p>
                    </div>
                </div>
                <button onclick="proceedToNext()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                    Next Step →
                </button>
            </div>
        `;
        canProceed = false;
        window.proceedToNext = () => {
            currentStep++;
            renderStep();
        };
    }
    
    function renderStep4(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl text-[#5A6B2D] mb-4">${step.text}</div>
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.num1} - ${step.num2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Original problem</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.result}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Answer!</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 ${step.hint}</p>
                    </div>
                </div>
                <button onclick="proceedToNext()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                    Next Step →
                </button>
            </div>
        `;
        canProceed = false;
        window.proceedToNext = () => {
            currentStep++;
            renderStep();
        };
    }
    
    function renderAnswer(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-5xl md:text-6xl font-bold text-[#5A6B2D] mb-4">
                        ${step.num1} - ${step.num2} = ${step.result}
                    </div>
                    <p class="text-2xl text-[#7A8B3B]">${step.text}</p>
                    <div class="mt-6 flex justify-center">
                        <div class="bg-green-100 rounded-full p-3">
                            <span class="text-4xl">🎉</span>
                        </div>
                    </div>
                </div>
                <button onclick="proceedToNext()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                    Continue →
                </button>
            </div>
        `;
        canProceed = false;
        window.proceedToNext = () => {
            currentStep++;
            renderStep();
        };
    }
    
    function renderMCQ(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl font-bold text-[#5A6B2D] mb-4">
                        ${step.num1} - ${step.num2} = ${step.result}
                    </div>
                    <div class="text-2xl text-[#5A6B2D] my-6">
                        Complete the equation:
                    </div>
                    <div class="text-xl text-[#5A6B2D] bg-white rounded-2xl p-6 inline-block max-w-md">
                        ${step.question}
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-8 max-w-md mx-auto">
                        ${step.options.map(opt => `
                            <button onclick="checkMCQ('${opt}')" class="mcq-btn bg-[#F0E8D0] hover:bg-[#E8D5A3] text-[#5A6B2D] text-2xl font-bold p-4 rounded-2xl transition-all hover:scale-105 border-2 border-[#D0C8A8] shadow-md">
                                ${opt}
                            </button>
                        `).join('')}
                    </div>
                    <div id="mcq-feedback" class="mt-6 text-xl min-h-[60px]"></div>
                </div>
            </div>
        `;
        canProceed = false;
        
        window.checkMCQ = (selected) => {
            const feedbackDiv = document.getElementById('mcq-feedback');
            const buttons = document.querySelectorAll('.mcq-btn');
            
            if (selected === step.correct) {
                feedbackDiv.innerHTML = '<span class="text-green-600 text-2xl">✅ Correct! Great job understanding the technique!</span>';
                canProceed = true;
                document.getElementById('nextBtn').classList.remove('opacity-50');
                
                buttons.forEach(btn => {
                    btn.disabled = true;
                });
            } else {
                feedbackDiv.innerHTML = '<span class="text-red-600 text-2xl">❌ Not quite! Remember: Round down to tens, subtract, then subtract the second ones and add the first ones. Try again!</span>';
                setTimeout(() => {
                    feedbackDiv.innerHTML = '';
                }, 2000);
            }
        };
    }
    
    function renderPractice(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-12 mb-6">
                    <div class="text-5xl mb-4">🏆</div>
                    <div class="text-3xl font-bold text-[#5A6B2D] mb-4">${step.text}</div>
                    <p class="text-xl text-[#7A8B3B] mt-4">Remember the steps:</p>
                    <div class="text-left bg-white rounded-2xl p-6 mt-4 max-w-md mx-auto">
                        <p class="text-lg text-[#5A6B2D]">1️⃣ Round down both numbers to the nearest ten</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">2️⃣ Subtract the tens</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">3️⃣ Subtract the second number's ones from that result</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">4️⃣ Add the first number's ones to get your answer</p>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-lg text-[#5A6B2D]">Example: 56 - 19 = ?</p>
                        <p class="text-lg text-[#7A8B3B] mt-2">Round down: 50 - 10 = 40</p>
                        <p class="text-lg text-[#7A8B3B]">Subtract ones: 40 - 9 = 31</p>
                        <p class="text-lg text-[#7A8B3B]">Add first ones: 31 + 6 = 37</p>
                        <p class="text-xl font-bold text-[#5A6B2D] mt-2">So 56 - 19 = 37!</p>
                    </div>
                </div>
                <button onclick="completeLesson()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                    Finish Lesson 🎉
                </button>
            </div>
        `;
        canProceed = false;
        // Make sure completeLesson is accessible
        window.completeLesson = completeLesson;
    }
    
    function nextStep() {
        if (!canProceed && steps[currentStep].type !== "example" && steps[currentStep].type !== "practice") {
            document.getElementById('helperMessage').innerHTML = 'Please answer the question first!';
            return;
        }
        
        if (currentStep < steps.length - 1) {
            currentStep++;
            renderStep();
        } else {
            completeLesson();
        }
    }
    
    function previousStep() {
        if (currentStep > 0) {
            currentStep--;
            renderStep();
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
                    lesson_number: 11
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 11 saved to database successfully!');
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
        
        // Check if already completed
        if (window.lessonCompleted) return;
        window.lessonCompleted = true;
        
        // Save to database first
        saveLessonCompletion().then(() => {
            // Save to localStorage as backup
            try {
                const savedProgress = localStorage.getItem('lessonProgress');
                let progress = savedProgress ? JSON.parse(savedProgress) : { completedLessons: [] };
                if (!progress.completedLessons.includes(11)) {
                    progress.completedLessons.push(11);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 11: Subtracting Two-Digit Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can subtract any two-digit numbers using this trick!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            if (nextBtn) nextBtn.classList.add('hidden');
            if (prevBtn) prevBtn.classList.add('hidden');
            
            const helperMessageEl = document.getElementById('helperMessage');
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You're a subtraction master! 🌟";
            const helperIconEl = document.getElementById('helperIcon');
            if (helperIconEl) helperIconEl.innerHTML = "🌟";
        }).catch(error => {
            console.error('Error saving to database:', error);
            // Still show completion even if database fails
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-8xl mb-4">🎉🏆🎉</div>
                    <h2 class="text-4xl font-bold text-[#7A8B3B] mb-4">CONGRATULATIONS!</h2>
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 11: Subtracting Two-Digit Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can subtract any two-digit numbers using this trick!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            if (nextBtn) nextBtn.classList.add('hidden');
            if (prevBtn) prevBtn.classList.add('hidden');
            
            const helperMessageEl = document.getElementById('helperMessage');
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You're a subtraction master! 🌟";
            const helperIconEl = document.getElementById('helperIcon');
            if (helperIconEl) helperIconEl.innerHTML = "🌟";
        });
    }
</script>
@endsection