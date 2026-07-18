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
                    <span class="text-3xl">💪</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 9: Adding Two-Digit Numbers</span>
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
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Learn how to add bigger two-digit numbers!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 9: Adding Two-Digit Numbers
    // Technique: Split into tens and ones, add tens, add ones, then add together
    // Format: Step-by-step demonstration + MCQ
    // ============================================

    let currentStep = 0;
    let canProceed = false;
    
    // Lesson steps (32 total - 8 examples with 5 steps each + 8 MCQ)
    const steps = [
        // Example 1: 34 + 17
        { type: "example", num1: 34, num2: 17, explanation: "Let's learn how to add 34 + 17 using a simple trick!" },
        { type: "step1", num1: 34, num2: 17, tens1: 30, ones1: 4, tens2: 10, ones2: 7, text: "Step 1: Split both numbers into tens and ones", hint: "34 = 30 + 4, 17 = 10 + 7" },
        { type: "step2", tens1: 30, tens2: 10, tensSum: 40, text: "Step 2: Add the tens together", hint: "30 + 10 = 40" },
        { type: "step3", ones1: 4, ones2: 7, onesSum: 11, text: "Step 3: Add the ones together", hint: "4 + 7 = 11" },
        { type: "step4", tensSum: 40, onesSum: 11, result: 51, text: "Step 4: Add the tens sum and ones sum together", hint: "40 + 11 = 51" },
        { type: "answer", num1: 34, num2: 17, result: 51, text: "So 34 + 17 = 51! 🎉" },
        
        // MCQ 1
        { type: "mcq", num1: 23, num2: 15, result: 38, question: "23 = 20 + 3, 15 = 10 + 5. Tens: 20 + 10 = 30, Ones: 3 + 5 = __, Total: 30 + __ = 38", correct: "8", options: ["6", "7", "8", "9"] },
        
        // Example 2: 29 + 36
        { type: "example", num1: 29, num2: 36, explanation: "Now let's try 29 + 36!" },
        { type: "step1", num1: 29, num2: 36, tens1: 20, ones1: 9, tens2: 30, ones2: 6, text: "Step 1: Split both numbers into tens and ones", hint: "29 = 20 + 9, 36 = 30 + 6" },
        { type: "step2", tens1: 20, tens2: 30, tensSum: 50, text: "Step 2: Add the tens together", hint: "20 + 30 = 50" },
        { type: "step3", ones1: 9, ones2: 6, onesSum: 15, text: "Step 3: Add the ones together", hint: "9 + 6 = 15" },
        { type: "step4", tensSum: 50, onesSum: 15, result: 65, text: "Step 4: Add the tens sum and ones sum together", hint: "50 + 15 = 65" },
        { type: "answer", num1: 29, num2: 36, result: 65, text: "So 29 + 36 = 65! 🎉" },
        
        // MCQ 2
        { type: "mcq", num1: 42, num2: 27, result: 69, question: "42 = 40 + 2, 27 = 20 + 7. Tens: 40 + 20 = 60, Ones: 2 + 7 = __, Total: 60 + __ = 69", correct: "9", options: ["7", "8", "9", "10"] },
        
        // Example 3: 45 + 28
        { type: "example", num1: 45, num2: 28, explanation: "Let's practice 45 + 28!" },
        { type: "step1", num1: 45, num2: 28, tens1: 40, ones1: 5, tens2: 20, ones2: 8, text: "Step 1: Split both numbers into tens and ones", hint: "45 = 40 + 5, 28 = 20 + 8" },
        { type: "step2", tens1: 40, tens2: 20, tensSum: 60, text: "Step 2: Add the tens together", hint: "40 + 20 = 60" },
        { type: "step3", ones1: 5, ones2: 8, onesSum: 13, text: "Step 3: Add the ones together", hint: "5 + 8 = 13" },
        { type: "step4", tensSum: 60, onesSum: 13, result: 73, text: "Step 4: Add the tens sum and ones sum together", hint: "60 + 13 = 73" },
        { type: "answer", num1: 45, num2: 28, result: 73, text: "So 45 + 28 = 73! 🎉" },
        
        // MCQ 3
        { type: "mcq", num1: 38, num2: 27, result: 65, question: "38 = 30 + 8, 27 = 20 + 7. Tens: 30 + 20 = 50, Ones: 8 + 7 = __, Total: 50 + __ = 65", correct: "15", options: ["13", "14", "15", "16"] },
        
        // Example 4: 56 + 38
        { type: "example", num1: 56, num2: 38, explanation: "Now a bigger one! 56 + 38" },
        { type: "step1", num1: 56, num2: 38, tens1: 50, ones1: 6, tens2: 30, ones2: 8, text: "Step 1: Split both numbers into tens and ones", hint: "56 = 50 + 6, 38 = 30 + 8" },
        { type: "step2", tens1: 50, tens2: 30, tensSum: 80, text: "Step 2: Add the tens together", hint: "50 + 30 = 80" },
        { type: "step3", ones1: 6, ones2: 8, onesSum: 14, text: "Step 3: Add the ones together", hint: "6 + 8 = 14" },
        { type: "step4", tensSum: 80, onesSum: 14, result: 94, text: "Step 4: Add the tens sum and ones sum together", hint: "80 + 14 = 94" },
        { type: "answer", num1: 56, num2: 38, result: 94, text: "So 56 + 38 = 94! 🎉" },
        
        // MCQ 4
        { type: "mcq", num1: 47, num2: 29, result: 76, question: "47 = 40 + 7, 29 = 20 + 9. Tens: 40 + 20 = 60, Ones: 7 + 9 = __, Total: 60 + __ = 76", correct: "16", options: ["14", "15", "16", "17"] },
        
        // Example 5: 63 + 29
        { type: "example", num1: 63, num2: 29, explanation: "Let's try 63 + 29!" },
        { type: "step1", num1: 63, num2: 29, tens1: 60, ones1: 3, tens2: 20, ones2: 9, text: "Step 1: Split both numbers into tens and ones", hint: "63 = 60 + 3, 29 = 20 + 9" },
        { type: "step2", tens1: 60, tens2: 20, tensSum: 80, text: "Step 2: Add the tens together", hint: "60 + 20 = 80" },
        { type: "step3", ones1: 3, ones2: 9, onesSum: 12, text: "Step 3: Add the ones together", hint: "3 + 9 = 12" },
        { type: "step4", tensSum: 80, onesSum: 12, result: 92, text: "Step 4: Add the tens sum and ones sum together", hint: "80 + 12 = 92" },
        { type: "answer", num1: 63, num2: 29, result: 92, text: "So 63 + 29 = 92! 🎉" },
        
        // MCQ 5
        { type: "mcq", num1: 54, num2: 38, result: 92, question: "54 = 50 + 4, 38 = 30 + 8. Tens: 50 + 30 = 80, Ones: 4 + 8 = __, Total: 80 + __ = 92", correct: "12", options: ["10", "11", "12", "13"] },
        
        // Example 6: 72 + 19
        { type: "example", num1: 72, num2: 19, explanation: "Let's practice 72 + 19!" },
        { type: "step1", num1: 72, num2: 19, tens1: 70, ones1: 2, tens2: 10, ones2: 9, text: "Step 1: Split both numbers into tens and ones", hint: "72 = 70 + 2, 19 = 10 + 9" },
        { type: "step2", tens1: 70, tens2: 10, tensSum: 80, text: "Step 2: Add the tens together", hint: "70 + 10 = 80" },
        { type: "step3", ones1: 2, ones2: 9, onesSum: 11, text: "Step 3: Add the ones together", hint: "2 + 9 = 11" },
        { type: "step4", tensSum: 80, onesSum: 11, result: 91, text: "Step 4: Add the tens sum and ones sum together", hint: "80 + 11 = 91" },
        { type: "answer", num1: 72, num2: 19, result: 91, text: "So 72 + 19 = 91! 🎉" },
        
        // MCQ 6
        { type: "mcq", num1: 68, num2: 27, result: 95, question: "68 = 60 + 8, 27 = 20 + 7. Tens: 60 + 20 = 80, Ones: 8 + 7 = __, Total: 80 + __ = 95", correct: "15", options: ["13", "14", "15", "16"] },
        
        // Example 7: 84 + 37
        { type: "example", num1: 84, num2: 37, explanation: "One more! 84 + 37" },
        { type: "step1", num1: 84, num2: 37, tens1: 80, ones1: 4, tens2: 30, ones2: 7, text: "Step 1: Split both numbers into tens and ones", hint: "84 = 80 + 4, 37 = 30 + 7" },
        { type: "step2", tens1: 80, tens2: 30, tensSum: 110, text: "Step 2: Add the tens together", hint: "80 + 30 = 110" },
        { type: "step3", ones1: 4, ones2: 7, onesSum: 11, text: "Step 3: Add the ones together", hint: "4 + 7 = 11" },
        { type: "step4", tensSum: 110, onesSum: 11, result: 121, text: "Step 4: Add the tens sum and ones sum together", hint: "110 + 11 = 121" },
        { type: "answer", num1: 84, num2: 37, result: 121, text: "So 84 + 37 = 121! 🎉" },
        
        // MCQ 7
        { type: "mcq", num1: 73, num2: 48, result: 121, question: "73 = 70 + 3, 48 = 40 + 8. Tens: 70 + 40 = 110, Ones: 3 + 8 = __, Total: 110 + __ = 121", correct: "11", options: ["9", "10", "11", "12"] },
        
        // Example 8: 91 + 28
        { type: "example", num1: 91, num2: 28, explanation: "Last example! 91 + 28" },
        { type: "step1", num1: 91, num2: 28, tens1: 90, ones1: 1, tens2: 20, ones2: 8, text: "Step 1: Split both numbers into tens and ones", hint: "91 = 90 + 1, 28 = 20 + 8" },
        { type: "step2", tens1: 90, tens2: 20, tensSum: 110, text: "Step 2: Add the tens together", hint: "90 + 20 = 110" },
        { type: "step3", ones1: 1, ones2: 8, onesSum: 9, text: "Step 3: Add the ones together", hint: "1 + 8 = 9" },
        { type: "step4", tensSum: 110, onesSum: 9, result: 119, text: "Step 4: Add the tens sum and ones sum together", hint: "110 + 9 = 119" },
        { type: "answer", num1: 91, num2: 28, result: 119, text: "So 91 + 28 = 119! 🎉" },
        
        // Final MCQ 8
        { type: "mcq", num1: 86, num2: 37, result: 123, question: "86 = 80 + 6, 37 = 30 + 7. Tens: 80 + 30 = 110, Ones: 6 + 7 = __, Total: 110 + __ = 123", correct: "13", options: ["11", "12", "13", "14"] },
        
        // Final Practice
        { type: "practice", text: "Great job! You've learned how to add two-digit numbers! Remember: split into tens and ones, add the tens, add the ones, then add them together!" }
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
        else if (step.type === "step1") message = "Step 1: Split both numbers into tens and ones";
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
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Tens</div>
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
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Tens</div>
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
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">First tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tensSum}</div>
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
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.tensSum}</div>
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
                feedbackDiv.innerHTML = '<span class="text-red-600 text-2xl">❌ Not quite! Remember: Split into tens and ones, add tens, add ones, then add them together. Try again!</span>';
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
                        <p class="text-lg text-[#5A6B2D]">1️⃣ Split both numbers into tens and ones</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">2️⃣ Add the tens together</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">3️⃣ Add the ones together</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">4️⃣ Add the tens sum and ones sum together</p>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-lg text-[#5A6B2D]">Example: 34 + 17 = ?</p>
                        <p class="text-lg text-[#7A8B3B] mt-2">34 = 30 + 4, 17 = 10 + 7</p>
                        <p class="text-lg text-[#7A8B3B]">Tens: 30 + 10 = 40</p>
                        <p class="text-lg text-[#7A8B3B]">Ones: 4 + 7 = 11</p>
                        <p class="text-lg text-[#7A8B3B]">Total: 40 + 11 = 51</p>
                        <p class="text-xl font-bold text-[#5A6B2D] mt-2">So 34 + 17 = 51!</p>
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
                    lesson_number: 9
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 9 saved to database successfully!');
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
                if (!progress.completedLessons.includes(9)) {
                    progress.completedLessons.push(9);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 9: Adding Two-Digit Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add any two-digit numbers using this trick!</p>
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
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You're becoming a math champion! 🌟";
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 9: Adding Two-Digit Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add any two-digit numbers using this trick!</p>
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
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You're becoming a math champion! 🌟";
            const helperIconEl = document.getElementById('helperIcon');
            if (helperIconEl) helperIconEl.innerHTML = "🌟";
        });
    }
</script>
@endsection