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
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 7: Adding Teen Numbers</span>
                </div>
            </div>

            <div class="px-8 mb-4">
                <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                    <span>Lesson Progress</span>
                    <span id="progressText">Step 1/24</span>
                </div>
                <div class="bg-[#E8D5A3] rounded-full h-3 overflow-hidden">
                    <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-500" style="width: 4%"></div>
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
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Learn how to add teen numbers easily!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 7: Adding Teen Numbers (10-19) + Numbers up to 10
    // Technique: Split teen number into 10 + ones, then add
    // Format: Step-by-step demonstration + MCQ
    // ============================================

    let currentStep = 0;
    let canProceed = false;
    
    // Lesson steps (24 total - 8 examples with 3 steps each + 8 MCQ)
    const steps = [
        // Example 1: 13 + 4
        { type: "example", num1: 13, num2: 4, explanation: "Let's learn how to add 13 + 4 using a simple trick!" },
        { type: "step1", teen: 13, ones: 3, num2: 4, text: "Step 1: Split the teen number into 10 and the ones", hint: "13 = 10 + 3" },
        { type: "step2", teen: 13, ones: 3, num2: 4, text: "Step 2: Add the ones together", hint: "3 + 4 = 7" },
        { type: "step3", teen: 13, ones: 3, num2: 4, result: 17, text: "Step 3: Add 10 to the sum", hint: "10 + 7 = 17" },
        { type: "answer", num1: 13, num2: 4, result: 17, text: "So 13 + 4 = 17! 🎉" },
        
        // MCQ 1
        { type: "mcq", num1: 14, num2: 3, result: 17, question: "14 = 10 + __, then __ + 3 = 7, then 10 + 7 = 17", correct: "4", options: ["3", "4", "5", "6"] },
        
        // Example 2: 15 + 3
        { type: "example", num1: 15, num2: 3, explanation: "Now let's try 15 + 3!" },
        { type: "step1", teen: 15, ones: 5, num2: 3, text: "Step 1: Split the teen number into 10 and the ones", hint: "15 = 10 + 5" },
        { type: "step2", teen: 15, ones: 5, num2: 3, text: "Step 2: Add the ones together", hint: "5 + 3 = 8" },
        { type: "step3", teen: 15, ones: 5, num2: 3, result: 18, text: "Step 3: Add 10 to the sum", hint: "10 + 8 = 18" },
        { type: "answer", num1: 15, num2: 3, result: 18, text: "So 15 + 3 = 18! 🎉" },
        
        // MCQ 2
        { type: "mcq", num1: 16, num2: 2, result: 18, question: "16 = 10 + __, then __ + 2 = 8, then 10 + 8 = 18", correct: "6", options: ["4", "5", "6", "7"] },
        
        // Example 3: 12 + 7
        { type: "example", num1: 12, num2: 7, explanation: "Let's practice 12 + 7!" },
        { type: "step1", teen: 12, ones: 2, num2: 7, text: "Step 1: Split the teen number into 10 and the ones", hint: "12 = 10 + 2" },
        { type: "step2", teen: 12, ones: 2, num2: 7, text: "Step 2: Add the ones together", hint: "2 + 7 = 9" },
        { type: "step3", teen: 12, ones: 2, num2: 7, result: 19, text: "Step 3: Add 10 to the sum", hint: "10 + 9 = 19" },
        { type: "answer", num1: 12, num2: 7, result: 19, text: "So 12 + 7 = 19! 🎉" },
        
        // MCQ 3
        { type: "mcq", num1: 11, num2: 8, result: 19, question: "11 = 10 + __, then __ + 8 = 9, then 10 + 9 = 19", correct: "1", options: ["0", "1", "2", "3"] },
        
        // Example 4: 17 + 9
        { type: "example", num1: 17, num2: 9, explanation: "Now a bigger one! 17 + 9" },
        { type: "step1", teen: 17, ones: 7, num2: 9, text: "Step 1: Split the teen number into 10 and the ones", hint: "17 = 10 + 7" },
        { type: "step2", teen: 17, ones: 7, num2: 9, text: "Step 2: Add the ones together", hint: "7 + 9 = 16" },
        { type: "step3", teen: 17, ones: 7, num2: 9, result: 26, text: "Step 3: Add 10 to the sum", hint: "10 + 16 = 26" },
        { type: "answer", num1: 17, num2: 9, result: 26, text: "So 17 + 9 = 26! 🎉" },
        
        // MCQ 4
        { type: "mcq", num1: 18, num2: 8, result: 26, question: "18 = 10 + __, then __ + 8 = 16, then 10 + 16 = 26", correct: "8", options: ["6", "7", "8", "9"] },
        
        // Example 5: 14 + 8
        { type: "example", num1: 14, num2: 8, explanation: "Let's try 14 + 8!" },
        { type: "step1", teen: 14, ones: 4, num2: 8, text: "Step 1: Split the teen number into 10 and the ones", hint: "14 = 10 + 4" },
        { type: "step2", teen: 14, ones: 4, num2: 8, text: "Step 2: Add the ones together", hint: "4 + 8 = 12" },
        { type: "step3", teen: 14, ones: 4, num2: 8, result: 22, text: "Step 3: Add 10 to the sum", hint: "10 + 12 = 22" },
        { type: "answer", num1: 14, num2: 8, result: 22, text: "So 14 + 8 = 22! 🎉" },
        
        // MCQ 5
        { type: "mcq", num1: 16, num2: 7, result: 23, question: "16 = 10 + __, then __ + 7 = 13, then 10 + 13 = 23", correct: "6", options: ["4", "5", "6", "7"] },
        
        // Example 6: 19 + 6
        { type: "example", num1: 19, num2: 6, explanation: "Let's practice 19 + 6!" },
        { type: "step1", teen: 19, ones: 9, num2: 6, text: "Step 1: Split the teen number into 10 and the ones", hint: "19 = 10 + 9" },
        { type: "step2", teen: 19, ones: 9, num2: 6, text: "Step 2: Add the ones together", hint: "9 + 6 = 15" },
        { type: "step3", teen: 19, ones: 9, num2: 6, result: 25, text: "Step 3: Add 10 to the sum", hint: "10 + 15 = 25" },
        { type: "answer", num1: 19, num2: 6, result: 25, text: "So 19 + 6 = 25! 🎉" },
        
        // MCQ 6
        { type: "mcq", num1: 13, num2: 9, result: 22, question: "13 = 10 + __, then __ + 9 = 12, then 10 + 12 = 22", correct: "3", options: ["2", "3", "4", "5"] },
        
        // Example 7: 11 + 9
        { type: "example", num1: 11, num2: 9, explanation: "One more! 11 + 9" },
        { type: "step1", teen: 11, ones: 1, num2: 9, text: "Step 1: Split the teen number into 10 and the ones", hint: "11 = 10 + 1" },
        { type: "step2", teen: 11, ones: 1, num2: 9, text: "Step 2: Add the ones together", hint: "1 + 9 = 10" },
        { type: "step3", teen: 11, ones: 1, num2: 9, result: 20, text: "Step 3: Add 10 to the sum", hint: "10 + 10 = 20" },
        { type: "answer", num1: 11, num2: 9, result: 20, text: "So 11 + 9 = 20! 🎉" },
        
        // MCQ 7
        { type: "mcq", num1: 15, num2: 9, result: 24, question: "15 = 10 + __, then __ + 9 = 14, then 10 + 14 = 24", correct: "5", options: ["3", "4", "5", "6"] },
        
        // Example 8: 12 + 9
        { type: "example", num1: 12, num2: 9, explanation: "Last example! 12 + 9" },
        { type: "step1", teen: 12, ones: 2, num2: 9, text: "Step 1: Split the teen number into 10 and the ones", hint: "12 = 10 + 2" },
        { type: "step2", teen: 12, ones: 2, num2: 9, text: "Step 2: Add the ones together", hint: "2 + 9 = 11" },
        { type: "step3", teen: 12, ones: 2, num2: 9, result: 21, text: "Step 3: Add 10 to the sum", hint: "10 + 11 = 21" },
        { type: "answer", num1: 12, num2: 9, result: 21, text: "So 12 + 9 = 21! 🎉" },
        
        // Final MCQ 8
        { type: "mcq", num1: 14, num2: 9, result: 23, question: "14 = 10 + __, then __ + 9 = 13, then 10 + 13 = 23", correct: "4", options: ["2", "3", "4", "5"] },
        
        // Final Practice
        { type: "practice", text: "Great job! You've learned how to add teen numbers! Remember: split the teen number into 10 + ones, add the ones, then add 10!" }
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
        else if (step.type === "step1") message = "Step 1: Split the teen number";
        else if (step.type === "step2") message = "Step 2: Add the ones together";
        else if (step.type === "step3") message = "Step 3: Add 10 to the sum";
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
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.teen}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Teen number</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ten</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#7A8B3B]">${step.ones}</div>
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
        const onesSum = step.ones + step.num2;
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl text-[#5A6B2D] mb-4">${step.text}</div>
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#7A8B3B]">${step.ones}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ones from teen</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.num2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Number to add</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#7A8B3B]">${onesSum}</div>
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
    
    function renderStep3(step, contentArea) {
        const onesSum = step.ones + step.num2;
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-8 mb-6">
                    <div class="text-3xl text-[#5A6B2D] mb-4">${step.text}</div>
                    <div class="flex justify-center items-center space-x-8 my-6">
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ten</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#7A8B3B]">${onesSum}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Sum of ones</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#7A8B3B]">${step.result}</div>
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
                feedbackDiv.innerHTML = '<span class="text-red-600 text-2xl">❌ Not quite! Remember: Split the teen number into 10 and the ones, then add the ones together, then add 10. Try again!</span>';
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
                        <p class="text-lg text-[#5A6B2D]">1️⃣ Split the teen number into 10 and the ones</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">2️⃣ Add the ones together</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">3️⃣ Add 10 to the sum</p>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-lg text-[#5A6B2D]">Example: 14 + 7 = ?</p>
                        <p class="text-lg text-[#7A8B3B] mt-2">14 = 10 + 4, then 4 + 7 = 11, then 10 + 11 = 21</p>
                        <p class="text-xl font-bold text-[#5A6B2D] mt-2">So 14 + 7 = 21!</p>
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
                    lesson_number: 7
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 7 saved to database successfully!');
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
                if (!progress.completedLessons.includes(7)) {
                    progress.completedLessons.push(7);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 7: Adding Teen Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add teen numbers quickly using this trick!</p>
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 7: Adding Teen Numbers!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add teen numbers quickly using this trick!</p>
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