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
                    <span class="text-3xl">➖➖</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 10: Subtracting Same Tens</span>
                </div>
            </div>

            <div class="px-8 mb-4">
                <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                    <span>Lesson Progress</span>
                    <span id="progressText">Step 1/28</span>
                </div>
                <div class="bg-[#E8D5A3] rounded-full h-3 overflow-hidden">
                    <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-500" style="width: 3.5%"></div>
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
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Learn how to subtract numbers with the same tens!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 10: Subtracting Same Tens
    // Technique: Split both numbers into tens and ones
    // Subtract tens first (they cancel out), then subtract ones
    // Format: Step-by-step demonstration + MCQ
    // ============================================

    let currentStep = 0;
    let canProceed = false;
    
    // Lesson steps (28 total - 7 examples with 4 steps each + 7 MCQ)
    const steps = [
        // Example 1: 14 - 12
        { type: "example", num1: 14, num2: 12, explanation: "Let's learn how to subtract 14 - 12 using a simple trick!" },
        { type: "step1", num1: 14, num2: 12, tens1: 10, ones1: 4, tens2: 10, ones2: 2, text: "Step 1: Split both numbers into tens and ones", hint: "14 = 10 + 4, 12 = 10 + 2" },
        { type: "step2", tens1: 10, tens2: 10, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "10 - 10 = 0" },
        { type: "step3", ones1: 4, ones2: 2, result: 2, text: "Step 3: Subtract the ones", hint: "4 - 2 = 2" },
        { type: "answer", num1: 14, num2: 12, result: 2, text: "So 14 - 12 = 2! 🎉" },
        
        // MCQ 1
        { type: "mcq", num1: 16, num2: 13, result: 3, question: "16 = 10 + 6, 13 = 10 + 3. Subtract tens: 10 - 10 = 0, Subtract ones: 6 - 3 = __", correct: "3", options: ["1", "2", "3", "4"] },
        
        // Example 2: 49 - 41
        { type: "example", num1: 49, num2: 41, explanation: "Now let's try 49 - 41!" },
        { type: "step1", num1: 49, num2: 41, tens1: 40, ones1: 9, tens2: 40, ones2: 1, text: "Step 1: Split both numbers into tens and ones", hint: "49 = 40 + 9, 41 = 40 + 1" },
        { type: "step2", tens1: 40, tens2: 40, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "40 - 40 = 0" },
        { type: "step3", ones1: 9, ones2: 1, result: 8, text: "Step 3: Subtract the ones", hint: "9 - 1 = 8" },
        { type: "answer", num1: 49, num2: 41, result: 8, text: "So 49 - 41 = 8! 🎉" },
        
        // MCQ 2
        { type: "mcq", num1: 37, num2: 34, result: 3, question: "37 = 30 + 7, 34 = 30 + 4. Subtract tens: 30 - 30 = 0, Subtract ones: 7 - 4 = __", correct: "3", options: ["1", "2", "3", "4"] },
        
        // Example 3: 28 - 25
        { type: "example", num1: 28, num2: 25, explanation: "Let's practice 28 - 25!" },
        { type: "step1", num1: 28, num2: 25, tens1: 20, ones1: 8, tens2: 20, ones2: 5, text: "Step 1: Split both numbers into tens and ones", hint: "28 = 20 + 8, 25 = 20 + 5" },
        { type: "step2", tens1: 20, tens2: 20, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "20 - 20 = 0" },
        { type: "step3", ones1: 8, ones2: 5, result: 3, text: "Step 3: Subtract the ones", hint: "8 - 5 = 3" },
        { type: "answer", num1: 28, num2: 25, result: 3, text: "So 28 - 25 = 3! 🎉" },
        
        // MCQ 3
        { type: "mcq", num1: 56, num2: 52, result: 4, question: "56 = 50 + 6, 52 = 50 + 2. Subtract tens: 50 - 50 = 0, Subtract ones: 6 - 2 = __", correct: "4", options: ["2", "3", "4", "5"] },
        
        // Example 4: 73 - 71
        { type: "example", num1: 73, num2: 71, explanation: "Now a bigger one! 73 - 71" },
        { type: "step1", num1: 73, num2: 71, tens1: 70, ones1: 3, tens2: 70, ones2: 1, text: "Step 1: Split both numbers into tens and ones", hint: "73 = 70 + 3, 71 = 70 + 1" },
        { type: "step2", tens1: 70, tens2: 70, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "70 - 70 = 0" },
        { type: "step3", ones1: 3, ones2: 1, result: 2, text: "Step 3: Subtract the ones", hint: "3 - 1 = 2" },
        { type: "answer", num1: 73, num2: 71, result: 2, text: "So 73 - 71 = 2! 🎉" },
        
        // MCQ 4
        { type: "mcq", num1: 85, num2: 82, result: 3, question: "85 = 80 + 5, 82 = 80 + 2. Subtract tens: 80 - 80 = 0, Subtract ones: 5 - 2 = __", correct: "3", options: ["1", "2", "3", "4"] },
        
        // Example 5: 62 - 60
        { type: "example", num1: 62, num2: 60, explanation: "Let's try 62 - 60!" },
        { type: "step1", num1: 62, num2: 60, tens1: 60, ones1: 2, tens2: 60, ones2: 0, text: "Step 1: Split both numbers into tens and ones", hint: "62 = 60 + 2, 60 = 60 + 0" },
        { type: "step2", tens1: 60, tens2: 60, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "60 - 60 = 0" },
        { type: "step3", ones1: 2, ones2: 0, result: 2, text: "Step 3: Subtract the ones", hint: "2 - 0 = 2" },
        { type: "answer", num1: 62, num2: 60, result: 2, text: "So 62 - 60 = 2! 🎉" },
        
        // MCQ 5
        { type: "mcq", num1: 48, num2: 45, result: 3, question: "48 = 40 + 8, 45 = 40 + 5. Subtract tens: 40 - 40 = 0, Subtract ones: 8 - 5 = __", correct: "3", options: ["1", "2", "3", "4"] },
        
        // Example 6: 97 - 94
        { type: "example", num1: 97, num2: 94, explanation: "Let's practice 97 - 94!" },
        { type: "step1", num1: 97, num2: 94, tens1: 90, ones1: 7, tens2: 90, ones2: 4, text: "Step 1: Split both numbers into tens and ones", hint: "97 = 90 + 7, 94 = 90 + 4" },
        { type: "step2", tens1: 90, tens2: 90, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "90 - 90 = 0" },
        { type: "step3", ones1: 7, ones2: 4, result: 3, text: "Step 3: Subtract the ones", hint: "7 - 4 = 3" },
        { type: "answer", num1: 97, num2: 94, result: 3, text: "So 97 - 94 = 3! 🎉" },
        
        // MCQ 6
        { type: "mcq", num1: 39, num2: 36, result: 3, question: "39 = 30 + 9, 36 = 30 + 6. Subtract tens: 30 - 30 = 0, Subtract ones: 9 - 6 = __", correct: "3", options: ["1", "2", "3", "4"] },
        
        // Example 7: 55 - 53
        { type: "example", num1: 55, num2: 53, explanation: "Last example! 55 - 53" },
        { type: "step1", num1: 55, num2: 53, tens1: 50, ones1: 5, tens2: 50, ones2: 3, text: "Step 1: Split both numbers into tens and ones", hint: "55 = 50 + 5, 53 = 50 + 3" },
        { type: "step2", tens1: 50, tens2: 50, text: "Step 2: Subtract the tens (they are the same, so they cancel out)", hint: "50 - 50 = 0" },
        { type: "step3", ones1: 5, ones2: 3, result: 2, text: "Step 3: Subtract the ones", hint: "5 - 3 = 2" },
        { type: "answer", num1: 55, num2: 53, result: 2, text: "So 55 - 53 = 2! 🎉" },
        
        // Final MCQ 7
        { type: "mcq", num1: 68, num2: 65, result: 3, question: "68 = 60 + 8, 65 = 60 + 5. Subtract tens: 60 - 60 = 0, Subtract ones: 8 - 5 = __", correct: "3", options: ["1", "2", "3", "4"] },
        
        // Final Practice
        { type: "practice", text: "Great job! You've learned how to subtract numbers with the same tens! Remember: split into tens and ones, subtract tens (they cancel), then subtract ones!" }
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
        else if (step.type === "step2") message = "Step 2: Subtract the tens (they cancel out)";
        else if (step.type === "step3") message = "Step 3: Subtract the ones";
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
                        <div class="text-3xl text-[#F4A623]">-</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#5A6B2D]">${step.tens2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second tens</div>
                        </div>
                        <div class="text-3xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">0</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">They cancel out!</div>
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
                        <div class="text-3xl text-[#F4A623]">-</div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-[#7A8B3B]">${step.ones2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Second ones</div>
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
                feedbackDiv.innerHTML = '<span class="text-red-600 text-2xl">❌ Not quite! Remember: Split into tens and ones, subtract tens (they cancel), then subtract ones. Try again!</span>';
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
                        <p class="text-lg text-[#5A6B2D] mt-2">2️⃣ Subtract the tens (they are the same, so they cancel out)</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">3️⃣ Subtract the ones to get your answer</p>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-lg text-[#5A6B2D]">Example: 49 - 41 = ?</p>
                        <p class="text-lg text-[#7A8B3B] mt-2">49 = 40 + 9, 41 = 40 + 1</p>
                        <p class="text-lg text-[#7A8B3B]">Tens: 40 - 40 = 0 (cancel)</p>
                        <p class="text-lg text-[#7A8B3B]">Ones: 9 - 1 = 8</p>
                        <p class="text-xl font-bold text-[#5A6B2D] mt-2">So 49 - 41 = 8!</p>
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
                    lesson_number: 10
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 10 saved to database successfully!');
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
                if (!progress.completedLessons.includes(10)) {
                    progress.completedLessons.push(10);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 10: Subtracting Same Tens!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can easily subtract numbers with the same tens!</p>
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
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You're a subtraction superstar! 🌟";
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 10: Subtracting Same Tens!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can easily subtract numbers with the same tens!</p>
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
            if (helperMessageEl) helperMessageEl.innerHTML = "Amazing work! You're a subtraction superstar! 🌟";
            const helperIconEl = document.getElementById('helperIcon');
            if (helperIconEl) helperIconEl.innerHTML = "🌟";
        });
    }
</script>
@endsection