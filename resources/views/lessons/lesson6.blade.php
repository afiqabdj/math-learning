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
                    <span class="text-3xl">🎯</span>
                    <span class="text-2xl font-bold text-[#5A6B2D]">Lesson 6: Bridging to 10 Technique</span>
                </div>
            </div>

            <div class="px-8 mb-4">
                <div class="flex justify-between text-sm text-[#5A6B2D] mb-1">
                    <span>Lesson Progress</span>
                    <span id="progressText">Step 1/16</span>
                </div>
                <div class="bg-[#E8D5A3] rounded-full h-3 overflow-hidden">
                    <div id="progressBar" class="bg-[#7A8B3B] h-full rounded-full transition-all duration-500" style="width: 6%"></div>
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
            <p class="text-[#5A6B2D] font-semibold" id="helperMessage">"Learn the secret trick to add numbers faster!"</p>
        </div>
    </div>
</div>

<script>
    // ============================================
    // LESSON 6: Bridging to 10 Technique
    // ============================================

    let currentStep = 0;
    let canProceed = false;
    let currentQuestion = null;
    let userAnswer = null;
    
    // Lesson steps (16 total - 8 examples + 8 MCQ)
    const steps = [
        // Example 1: 7 + 8
        { type: "example", num1: 7, num2: 8, explanation: "Let's learn how to add 7 + 8 using a special trick!" },
        { type: "step1", num1: 7, num2: 8, text: "Step 1: Pick the bigger number that is closest to 10", bigger: 8, smaller: 7, hint: "8 is closer to 10 than 7" },
        { type: "step2", num1: 7, num2: 8, text: "Step 2: How many more to make 10?", bigger: 8, needed: 2, hint: "8 needs 2 more to become 10" },
        { type: "step3", num1: 7, num2: 8, text: "Step 3: Take that number from the smaller number", smaller: 7, take: 2, remaining: 5, hint: "7 - 2 = 5" },
        { type: "step4", num1: 7, num2: 8, text: "Step 4: Add 10 to the remaining number", remaining: 5, result: 15, hint: "5 + 10 = 15" },
        { type: "answer", num1: 7, num2: 8, result: 15, text: "So 7 + 8 = 15! 🎉" },
        
        // MCQ 1: 7 + 6 = 13
        { type: "mcq", num1: 7, num2: 6, result: 13, question: "(7 ⬜ 3) + (6 ⬜ 3) = 13", correct: "+ , -", options: ["+ , -", "- , +", "+ , +", "- , -"] },
        
        // Example 2: 6 + 9
        { type: "example", num1: 6, num2: 9, explanation: "Now let's try 6 + 9!" },
        { type: "step1", num1: 6, num2: 9, text: "Step 1: Pick the bigger number closest to 10", bigger: 9, smaller: 6, hint: "9 is closer to 10 than 6" },
        { type: "step2", num1: 6, num2: 9, text: "Step 2: How many more to make 10?", bigger: 9, needed: 1, hint: "9 needs 1 more to become 10" },
        { type: "step3", num1: 6, num2: 9, text: "Step 3: Take that number from the smaller number", smaller: 6, take: 1, remaining: 5, hint: "6 - 1 = 5" },
        { type: "step4", num1: 6, num2: 9, text: "Step 4: Add 10 to the remaining number", remaining: 5, result: 15, hint: "5 + 10 = 15" },
        { type: "answer", num1: 6, num2: 9, result: 15, text: "So 6 + 9 = 15! 🎉" },
        
        // MCQ 2: 8 + 5 = 13
        { type: "mcq", num1: 8, num2: 5, result: 13, question: "(8 ⬜ 2) + (5 ⬜ 2) = 13", correct: "+ , -", options: ["+ , -", "- , +", "+ , +", "- , -"] },
        
        // Example 3: 5 + 8
        { type: "example", num1: 5, num2: 8, explanation: "Let's practice 5 + 8!" },
        { type: "step1", num1: 5, num2: 8, text: "Step 1: Pick the bigger number closest to 10", bigger: 8, smaller: 5, hint: "8 is closer to 10 than 5" },
        { type: "step2", num1: 5, num2: 8, text: "Step 2: How many more to make 10?", bigger: 8, needed: 2, hint: "8 needs 2 more to become 10" },
        { type: "step3", num1: 5, num2: 8, text: "Step 3: Take that number from the smaller number", smaller: 5, take: 2, remaining: 3, hint: "5 - 2 = 3" },
        { type: "step4", num1: 5, num2: 8, text: "Step 4: Add 10 to the remaining number", remaining: 3, result: 13, hint: "3 + 10 = 13" },
        { type: "answer", num1: 5, num2: 8, result: 13, text: "So 5 + 8 = 13! 🎉" },
        
        // MCQ 3: 9 + 4 = 13
        { type: "mcq", num1: 9, num2: 4, result: 13, question: "(9 ⬜ 1) + (4 ⬜ 1) = 13", correct: "+ , -", options: ["+ , -", "- , +", "+ , +", "- , -"] },
        
        // Example 4: 7 + 9
        { type: "example", num1: 7, num2: 9, explanation: "One more! Try 7 + 9!" },
        { type: "step1", num1: 7, num2: 9, text: "Step 1: Pick the bigger number closest to 10", bigger: 9, smaller: 7, hint: "9 is closer to 10 than 7" },
        { type: "step2", num1: 7, num2: 9, text: "Step 2: How many more to make 10?", bigger: 9, needed: 1, hint: "9 needs 1 more to become 10" },
        { type: "step3", num1: 7, num2: 9, text: "Step 3: Take that number from the smaller number", smaller: 7, take: 1, remaining: 6, hint: "7 - 1 = 6" },
        { type: "step4", num1: 7, num2: 9, text: "Step 4: Add 10 to the remaining number", remaining: 6, result: 16, hint: "6 + 10 = 16" },
        { type: "answer", num1: 7, num2: 9, result: 16, text: "So 7 + 9 = 16! 🎉" },
        
        // MCQ 4: 6 + 8 = 14
        { type: "mcq", num1: 6, num2: 8, result: 14, question: "(6 ⬜ 2) + (8 ⬜ 2) = 14", correct: "- , +", options: ["+ , -", "- , +", "+ , +", "- , -"] },
        
        // Example 5: 8 + 7
        { type: "example", num1: 8, num2: 7, explanation: "Let's review with 8 + 7!" },
        { type: "step1", num1: 8, num2: 7, text: "Step 1: Pick the bigger number closest to 10", bigger: 8, smaller: 7, hint: "8 is closer to 10 than 7" },
        { type: "step2", num1: 8, num2: 7, text: "Step 2: How many more to make 10?", bigger: 8, needed: 2, hint: "8 needs 2 more to become 10" },
        { type: "step3", num1: 8, num2: 7, text: "Step 3: Take that number from the smaller number", smaller: 7, take: 2, remaining: 5, hint: "7 - 2 = 5" },
        { type: "step4", num1: 8, num2: 7, text: "Step 4: Add 10 to the remaining number", remaining: 5, result: 15, hint: "5 + 10 = 15" },
        { type: "answer", num1: 8, num2: 7, result: 15, text: "So 8 + 7 = 15! 🎉" },
        
        // MCQ 5: 9 + 6 = 15
        { type: "mcq", num1: 9, num2: 6, result: 15, question: "(9 ⬜ 1) + (6 ⬜ 1) = 15", correct: "+ , -", options: ["+ , -", "- , +", "+ , +", "- , -"] },
        
        // Example 6: 6 + 7
        { type: "example", num1: 6, num2: 7, explanation: "Let's try 6 + 7!" },
        { type: "step1", num1: 6, num2: 7, text: "Step 1: Pick the bigger number closest to 10", bigger: 7, smaller: 6, hint: "7 is closer to 10 than 6" },
        { type: "step2", num1: 6, num2: 7, text: "Step 2: How many more to make 10?", bigger: 7, needed: 3, hint: "7 needs 3 more to become 10" },
        { type: "step3", num1: 6, num2: 7, text: "Step 3: Take that number from the smaller number", smaller: 6, take: 3, remaining: 3, hint: "6 - 3 = 3" },
        { type: "step4", num1: 6, num2: 7, text: "Step 4: Add 10 to the remaining number", remaining: 3, result: 13, hint: "3 + 10 = 13" },
        { type: "answer", num1: 6, num2: 7, result: 13, text: "So 6 + 7 = 13! 🎉" },
        
        // Final Practice
        { type: "practice", text: "Great job! You've learned the bridging to 10 technique! Practice this method whenever you add numbers!" }
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
        
        const helperMessageEl = document.getElementById('helperMessage');
        let message = "";
        if (step.type === "example") message = "Let's learn a new trick!";
        else if (step.type === "step1") message = "Step 1: Pick the bigger number";
        else if (step.type === "step2") message = "Step 2: How many more to make 10?";
        else if (step.type === "step3") message = "Step 3: Take from the smaller number";
        else if (step.type === "step4") message = "Step 4: Add 10 to what's left";
        else if (step.type === "answer") message = "Great job!";
        else if (step.type === "mcq") message = "Choose the correct answer!";
        else if (step.type === "practice") message = "You've got this!";
        
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
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.num1}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Number 1</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.num2}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Number 2</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 <span class="font-bold">${step.hint}</span></p>
                        <p class="text-xl text-[#7A8B3B] mt-2">✓ The bigger number is <span class="font-bold text-[#5A6B2D]">${step.bigger}</span></p>
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
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.bigger}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Bigger number</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">→</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Target</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 ${step.bigger} needs <span class="font-bold text-2xl text-[#F4A623]">${step.needed}</span> more to reach 10</p>
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
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.smaller}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Smaller number</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">-</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#F4A623]">${step.take}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Take away</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.remaining}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Remaining</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 ${step.smaller} - ${step.take} = <span class="font-bold">${step.remaining}</span></p>
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
                            <div class="text-6xl font-bold text-[#5A6B2D]">${step.remaining}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Remaining</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">+</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#5A6B2D]">10</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Ten</div>
                        </div>
                        <div class="text-4xl text-[#F4A623]">=</div>
                        <div class="text-center">
                            <div class="text-6xl font-bold text-[#7A8B3B]">${step.result}</div>
                            <div class="text-sm text-[#8C9DB3] mt-1">Answer!</div>
                        </div>
                    </div>
                    <div class="bg-[#F0E8D0] rounded-2xl p-4 mt-4">
                        <p class="text-xl text-[#5A6B2D]">💡 ${step.remaining} + 10 = <span class="font-bold text-2xl text-[#7A8B3B]">${step.result}</span></p>
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
                    <div class="text-3xl font-bold text-[#5A6B2D] bg-white rounded-2xl p-6 inline-block">
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
                feedbackDiv.innerHTML = '<span class="text-red-600 text-2xl">❌ Not quite! Remember: we add to make 10, then subtract from the other number. Try again!</span>';
                setTimeout(() => {
                    feedbackDiv.innerHTML = '';
                }, 2000);
            }
        };
    }
    
    // FIXED: This is the corrected renderPractice function
    function renderPractice(step, contentArea) {
        contentArea.innerHTML = `
            <div class="text-center">
                <div class="bg-[#E8F0E0] rounded-3xl p-12 mb-6">
                    <div class="text-5xl mb-4">🏆</div>
                    <div class="text-3xl font-bold text-[#5A6B2D] mb-4">${step.text}</div>
                    <p class="text-xl text-[#7A8B3B] mt-4">Remember the steps:</p>
                    <div class="text-left bg-white rounded-2xl p-6 mt-4 max-w-md mx-auto">
                        <p class="text-lg text-[#5A6B2D]">1️⃣ Pick the bigger number closest to 10</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">2️⃣ Find how many more to make 10</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">3️⃣ Take that from the smaller number</p>
                        <p class="text-lg text-[#5A6B2D] mt-2">4️⃣ Add 10 to what's left</p>
                    </div>
                </div>
                <button onclick="completeLesson()" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md" id="finishLessonBtn">
                    Finish Lesson 🎉
                </button>
            </div>
        `;
        canProceed = false;
    }
    
    function nextStep() {
        if (!canProceed && steps[currentStep].type !== "example" && steps[currentStep].type !== "practice") {
            document.getElementById('helperMessage').innerHTML = '"Complete the question first!"';
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
            canProceed = true;
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
                    lesson_number: 6
                })
            });
            const data = await response.json();
            console.log('Lesson saved response:', data);
            if (data.success) {
                console.log('✅ Lesson 6 saved to database successfully!');
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
                if (!progress.completedLessons.includes(6)) {
                    progress.completedLessons.push(6);
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
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 6: Bridging to 10 Technique!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add numbers faster using this special trick!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('prevBtn').classList.add('hidden');
            document.getElementById('helperMessage').innerHTML = '"Amazing work! You\'ve learned a powerful math trick! 🌟"';
            document.getElementById('helperIcon').innerHTML = "🌟";
        }).catch(error => {
            console.error('Error saving to database:', error);
            // Still show completion even if database fails
            const contentArea = document.getElementById('contentArea');
            contentArea.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-8xl mb-4">🎉🏆🎉</div>
                    <h2 class="text-4xl font-bold text-[#7A8B3B] mb-4">CONGRATULATIONS!</h2>
                    <p class="text-2xl text-[#5A6B2D] mb-6">You completed Lesson 6: Bridging to 10 Technique!</p>
                    <p class="text-xl text-[#8C9DB3] mb-8">Now you can add numbers faster using this special trick!</p>
                    <button onclick="window.location.href='/lessons'" class="bg-[#7A8B3B] hover:bg-[#5A6B2D] text-white px-8 py-3 rounded-full font-bold text-xl transition-all hover:scale-105 shadow-md">
                        Back to Lessons →
                    </button>
                </div>
            `;
            
            document.getElementById('nextBtn').classList.add('hidden');
            document.getElementById('prevBtn').classList.add('hidden');
            document.getElementById('helperMessage').innerHTML = '"Amazing work! You\'ve learned a powerful math trick! 🌟"';
            document.getElementById('helperIcon').innerHTML = "🌟";
        });
    }
</script>
@endsection