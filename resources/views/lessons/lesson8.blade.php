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
                    <span class="text-3xl">🔟🔟</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 8: Adding Teen Numbers + Teen Numbers</span>
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
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Learn how to add teen numbers to teen numbers!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 8: Adding Teen Numbers + Teen Numbers
    // Technique: Split both into 10 + ones, add 10s first, then ones
    // Format: Step-by-step demonstration + MCQ
    // ============================================

    let currentStep = 0;
    let canProceed = false;
    
    // Lesson steps (32 total - 8 examples with 4 steps each + 8 MCQ)
    const steps = [
        // Example 1: 19 + 17
        { type: "example", num1: 19, num2: 17, explanation: "Let's learn how to add 19 + 17 using a simple trick!" },
        { type: "step1", num1: 19, num2: 17, ones1: 9, ones2: 7, text: "Step 1: Split both teen numbers into 10 and ones", hint: "19 = 10 + 9, 17 = 10 + 7" },
        { type: "step2", num1: 19, num2: 17, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 9, ones2: 7, onesSum: 16, text: "Step 3: Add the ones together", hint: "9 + 7 = 16" },
        { type: "step4", tens: 20, onesSum: 16, result: 36, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 16 = 36" },
        { type: "answer", num1: 19, num2: 17, result: 36, text: "So 19 + 17 = 36! 🎉" },
        
        // MCQ 1
        { type: "mcq", num1: 18, num2: 15, result: 33, question: "18 = 10 + 8, 15 = 10 + 5. Tens: 10 + 10 = 20, Ones: 8 + 5 = __, Total: 20 + __ = 33", correct: "13", options: ["11", "12", "13", "14"] },
        
        // Example 2: 14 + 13
        { type: "example", num1: 14, num2: 13, explanation: "Now let's try 14 + 13!" },
        { type: "step1", num1: 14, num2: 13, ones1: 4, ones2: 3, text: "Step 1: Split both teen numbers into 10 and ones", hint: "14 = 10 + 4, 13 = 10 + 3" },
        { type: "step2", num1: 14, num2: 13, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 4, ones2: 3, onesSum: 7, text: "Step 3: Add the ones together", hint: "4 + 3 = 7" },
        { type: "step4", tens: 20, onesSum: 7, result: 27, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 7 = 27" },
        { type: "answer", num1: 14, num2: 13, result: 27, text: "So 14 + 13 = 27! 🎉" },
        
        // MCQ 2
        { type: "mcq", num1: 16, num2: 12, result: 28, question: "16 = 10 + 6, 12 = 10 + 2. Tens: 10 + 10 = 20, Ones: 6 + 2 = __, Total: 20 + __ = 28", correct: "8", options: ["6", "7", "8", "9"] },
        
        // Example 3: 18 + 14
        { type: "example", num1: 18, num2: 14, explanation: "Let's practice 18 + 14!" },
        { type: "step1", num1: 18, num2: 14, ones1: 8, ones2: 4, text: "Step 1: Split both teen numbers into 10 and ones", hint: "18 = 10 + 8, 14 = 10 + 4" },
        { type: "step2", num1: 18, num2: 14, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 8, ones2: 4, onesSum: 12, text: "Step 3: Add the ones together", hint: "8 + 4 = 12" },
        { type: "step4", tens: 20, onesSum: 12, result: 32, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 12 = 32" },
        { type: "answer", num1: 18, num2: 14, result: 32, text: "So 18 + 14 = 32! 🎉" },
        
        // MCQ 3
        { type: "mcq", num1: 15, num2: 17, result: 32, question: "15 = 10 + 5, 17 = 10 + 7. Tens: 10 + 10 = 20, Ones: 5 + 7 = __, Total: 20 + __ = 32", correct: "12", options: ["10", "11", "12", "13"] },
        
        // Example 4: 19 + 16
        { type: "example", num1: 19, num2: 16, explanation: "Now a bigger one! 19 + 16" },
        { type: "step1", num1: 19, num2: 16, ones1: 9, ones2: 6, text: "Step 1: Split both teen numbers into 10 and ones", hint: "19 = 10 + 9, 16 = 10 + 6" },
        { type: "step2", num1: 19, num2: 16, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 9, ones2: 6, onesSum: 15, text: "Step 3: Add the ones together", hint: "9 + 6 = 15" },
        { type: "step4", tens: 20, onesSum: 15, result: 35, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 15 = 35" },
        { type: "answer", num1: 19, num2: 16, result: 35, text: "So 19 + 16 = 35! 🎉" },
        
        // MCQ 4
        { type: "mcq", num1: 17, num2: 18, result: 35, question: "17 = 10 + 7, 18 = 10 + 8. Tens: 10 + 10 = 20, Ones: 7 + 8 = __, Total: 20 + __ = 35", correct: "15", options: ["13", "14", "15", "16"] },
        
        // Example 5: 13 + 18
        { type: "example", num1: 13, num2: 18, explanation: "Let's try 13 + 18!" },
        { type: "step1", num1: 13, num2: 18, ones1: 3, ones2: 8, text: "Step 1: Split both teen numbers into 10 and ones", hint: "13 = 10 + 3, 18 = 10 + 8" },
        { type: "step2", num1: 13, num2: 18, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 3, ones2: 8, onesSum: 11, text: "Step 3: Add the ones together", hint: "3 + 8 = 11" },
        { type: "step4", tens: 20, onesSum: 11, result: 31, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 11 = 31" },
        { type: "answer", num1: 13, num2: 18, result: 31, text: "So 13 + 18 = 31! 🎉" },
        
        // MCQ 5
        { type: "mcq", num1: 12, num2: 19, result: 31, question: "12 = 10 + 2, 19 = 10 + 9. Tens: 10 + 10 = 20, Ones: 2 + 9 = __, Total: 20 + __ = 31", correct: "11", options: ["9", "10", "11", "12"] },
        
        // Example 6: 16 + 19
        { type: "example", num1: 16, num2: 19, explanation: "Let's practice 16 + 19!" },
        { type: "step1", num1: 16, num2: 19, ones1: 6, ones2: 9, text: "Step 1: Split both teen numbers into 10 and ones", hint: "16 = 10 + 6, 19 = 10 + 9" },
        { type: "step2", num1: 16, num2: 19, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 6, ones2: 9, onesSum: 15, text: "Step 3: Add the ones together", hint: "6 + 9 = 15" },
        { type: "step4", tens: 20, onesSum: 15, result: 35, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 15 = 35" },
        { type: "answer", num1: 16, num2: 19, result: 35, text: "So 16 + 19 = 35! 🎉" },
        
        // MCQ 6
        { type: "mcq", num1: 14, num2: 17, result: 31, question: "14 = 10 + 4, 17 = 10 + 7. Tens: 10 + 10 = 20, Ones: 4 + 7 = __, Total: 20 + __ = 31", correct: "11", options: ["9", "10", "11", "12"] },
        
        // Example 7: 15 + 19
        { type: "example", num1: 15, num2: 19, explanation: "One more! 15 + 19" },
        { type: "step1", num1: 15, num2: 19, ones1: 5, ones2: 9, text: "Step 1: Split both teen numbers into 10 and ones", hint: "15 = 10 + 5, 19 = 10 + 9" },
        { type: "step2", num1: 15, num2: 19, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 5, ones2: 9, onesSum: 14, text: "Step 3: Add the ones together", hint: "5 + 9 = 14" },
        { type: "step4", tens: 20, onesSum: 14, result: 34, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 14 = 34" },
        { type: "answer", num1: 15, num2: 19, result: 34, text: "So 15 + 19 = 34! 🎉" },
        
        // MCQ 7
        { type: "mcq", num1: 13, num2: 16, result: 29, question: "13 = 10 + 3, 16 = 10 + 6. Tens: 10 + 10 = 20, Ones: 3 + 6 = __, Total: 20 + __ = 29", correct: "9", options: ["7", "8", "9", "10"] },
        
        // Example 8: 18 + 17
        { type: "example", num1: 18, num2: 17, explanation: "Last example! 18 + 17" },
        { type: "step1", num1: 18, num2: 17, ones1: 8, ones2: 7, text: "Step 1: Split both teen numbers into 10 and ones", hint: "18 = 10 + 8, 17 = 10 + 7" },
        { type: "step2", num1: 18, num2: 17, tens: 20, text: "Step 2: Add the tens together", hint: "10 + 10 = 20" },
        { type: "step3", ones1: 8, ones2: 7, onesSum: 15, text: "Step 3: Add the ones together", hint: "8 + 7 = 15" },
        { type: "step4", tens: 20, onesSum: 15, result: 35, text: "Step 4: Add the tens sum and ones sum together", hint: "20 + 15 = 35" },
        { type: "answer", num1: 18, num2: 17, result: 35, text: "So 18 + 17 = 35! 🎉" },
        
        // Final MCQ 8
        { type: "mcq", num1: 17, num2: 19, result: 36, question: "17 = 10 + 7, 19 = 10 + 9. Tens: 10 + 10 = 20, Ones: 7 + 9 = __, Total: 20 + __ = 36", correct: "16", options: ["14", "15", "16", "17"] },
        
        // Final Practice
        { type: "practice", text: "Great job! You've learned how to add teen numbers to teen numbers! Remember: split into 10 + ones, add the tens, add the ones, then add them together!" }
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
        else if (step.type === "step1") message = "Step 1: Split both numbers into 10 + ones";
        else if (step.type === "step2") message = "Step 2: Add the tens together";
        else if (step.type === "step3") message = "Step 3: Add the ones together";
        else if (step.type === "step4") message = "Step 4: Add the tens sum and ones sum together";
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
                        ${step.num1} + ${step.num2} = ?
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
                    <div class="flex justify-center items-center space-x-4 my-6 flex-wrap">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.num1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First number</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ten</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.ones1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ones</div>
                        </div>
                    </div>
                    <div class="flex justify-center items-center space-x-4 my-6 flex-wrap">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.num2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second number</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ten</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.ones2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ones</div>
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
                            <div class="text-5xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First ten</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second ten</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tens}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Sum of tens</div>
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
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.ones1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First ones</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.ones2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second ones</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.onesSum}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Sum of ones</div>
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
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tens}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Sum of tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.onesSum}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Sum of ones</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.result}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Final Answer!</div>
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
                        ${step.num1} + ${step.num2} = ${step.result}
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
                        ${step.num1} + ${step.num2} = ${step.result}
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
                feedbackDiv.innerHTML = '<span class="text-red-600 text-2xl">❌ Not quite! Remember: Split into 10 + ones, add tens, add ones, then add them together. Try again!</span>';
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
                        <p class="text-lg text-[#5A6B2D]">1️⃣ Split both numbers into 10 + ones</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">2️⃣ Add the tens together (10 + 10 = 20)</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">3️⃣ Add the ones together</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">4️⃣ Add the tens sum and ones sum together</p>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-lg text-[#5A6B2D]">Example: 19 + 17 = ?</p>
                        <p class="text-lg text-[#7A8B3B] mt-2">19 = 10 + 9, 17 = 10 + 7</p>
                        <p class="text-lg text-[#7A8B3B]">Tens: 10 + 10 = 20</p>
                        <p class="text-lg text-[#7A8B3B]">Ones: 9 + 7 = 16</p>
                        <p class="text-lg text-[#7A8B3B]">Total: 20 + 16 = 36</p>
                        <p class="text-xl font-bold text-[#5A6B2D] mt-2">So 19 + 17 = 36!</p>
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
                    lesson_number: 8
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 8 saved to database successfully!');
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
                if (!progress.completedLessons.includes(8)) {
                    progress.completedLessons.push(8);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 8: Adding Teen Numbers + Teen Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add teen numbers to teen numbers using this trick!</p>
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
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You've mastered teen number addition! 🌟";
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 8: Adding Teen Numbers + Teen Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add teen numbers to teen numbers using this trick!</p>
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
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You've mastered teen number addition! 🌟";
            const helperIconEl = document.getElementById('helperIcon');
            if (helperIconEl) helperIconEl.innerHTML = "🌟";
        });
    }
</script>
@endsection