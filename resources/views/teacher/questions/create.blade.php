@extends('layouts.teachers')

@section('content')
<div class="relative z-10 min-h-screen py-8 px-4">
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                    <span>➕</span>
                    <span>Create Question Set</span>
                </h1>
                <p class="text-white/70 text-sm mt-1">Generate custom addition questions for your students</p>
            </div>
            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <a href="{{ route('teacher.questions.index') }}" 
                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center gap-2">
                    <span>⬅️</span> Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl max-w-6xl mx-auto">
        <form id="questionForm" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                    <input type="text" id="title" name="title" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-lg"
                           placeholder="e.g., Addition Practice - Level 1" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Operation Type</label>
                    <div class="w-full px-4 py-3 bg-gray-100 rounded-lg border-2 border-gray-200 text-lg text-gray-700 font-semibold">
                        ➕ Addition Only
                    </div>
                    <input type="hidden" name="operation_type" value="addition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="2"
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-lg"
                          placeholder="Optional description for this question set"></textarea>
            </div>

            <!-- Question Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Number of Questions (max 50 questions) *</label>
                    <input type="number" id="number_of_questions" name="number_of_questions" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-lg"
                           value="10" min="1" max="50" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Time Limit (seconds)</label>
                    <input type="number" id="time_limit" name="time_limit" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-lg"
                           placeholder="Leave empty for no limit" min="10">
                </div>
            </div>

            <!-- Number Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div hidden>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Min Value</label>
                    <input type="number" id="min_value" name="min_value" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-lg"
                           value="0" min="0" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Max Value</label>
                    <input type="number" id="max_value" name="max_value" 
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#5C3A1E] focus:ring-2 focus:ring-[#5C3A1E]/20 text-lg"
                           value="20" min="1" required>
                </div>
            </div>

            <!-- Public Option -->
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_public" name="is_public" value="1"
                       class="w-5 h-5 rounded border-gray-300 text-[#5C3A1E] focus:ring-[#5C3A1E]">
                <label for="is_public" class="text-gray-700 font-medium text-lg">
                    🌍 Make this set public 
                </label>
            </div>

            <!-- Generate & Preview Section -->
            <div class="border-t-2 border-gray-200 pt-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-[#5C3A1E]">📊 Preview Questions</h3>
                    <div class="flex gap-3">
                        <button type="button" id="regenerateBtn" 
                                class="bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-4 py-2 rounded-lg font-semibold transition-all">
                            🔄 Regenerate Questions
                        </button>
                    </div>
                </div>
                
                <!-- Question Preview Container -->
                <div id="questionPreviewContainer" class="bg-gray-50 rounded-lg p-4 min-h-[200px]">
                    <div id="questionPreview" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Questions will be rendered here -->
                    </div>
                </div>
                
                <div id="previewStatus" class="text-sm text-gray-400 mt-2 text-center">
                    Click "Regenerate Questions" to generate a new set of questions
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-4 pt-4">
                <button type="submit" id="saveBtn" disabled
                        class="flex-1 bg-gray-400 text-white px-6 py-3 rounded-lg font-bold text-lg transition-all cursor-not-allowed">
                    ✅ Generate & Save
                </button>
                <button type="reset" 
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium text-lg transition-all">
                    Clear
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let currentQuestions = [];
    let isGenerating = false;

    // Generate questions
    async function generateQuestions() {
        const minVal = parseInt(document.getElementById('min_value').value) || 0;
        const maxVal = parseInt(document.getElementById('max_value').value) || 20;
        const count = parseInt(document.getElementById('number_of_questions').value) || 10;

        // Validate
        if (minVal >= maxVal) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Range',
                text: 'Max value must be greater than min value',
                confirmButtonColor: '#5C3A1E'
            });
            return;
        }

        if (count < 1 || count > 50) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Count',
                text: 'Number of questions must be between 1 and 50',
                confirmButtonColor: '#5C3A1E'
            });
            return;
        }

        isGenerating = true;
        document.getElementById('regenerateBtn').disabled = true;
        document.getElementById('regenerateBtn').innerHTML = '⏳ Generating...';

        try {
            const response = await fetch('{{ route("teacher.questions.preview") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    number_of_questions: count,
                    min_value: minVal,
                    max_value: maxVal
                })
            });

            const data = await response.json();

            if (data.success) {
                currentQuestions = data.questions;
                renderQuestions(currentQuestions);
                document.getElementById('saveBtn').disabled = false;
                document.getElementById('saveBtn').className = 
                    'flex-1 bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white px-6 py-3 rounded-lg font-bold text-lg transition-all shadow-lg';
                document.getElementById('saveBtn').innerHTML = '✅ Save Question Set';
                document.getElementById('previewStatus').textContent = 
                    `✅ ${currentQuestions.length} questions generated. Review them below.`;
                document.getElementById('previewStatus').className = 'text-sm text-green-600 mt-2 text-center font-semibold';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.error || 'Failed to generate questions',
                    confirmButtonColor: '#5C3A1E'
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#5C3A1E'
            });
        }

        isGenerating = false;
        document.getElementById('regenerateBtn').disabled = false;
        document.getElementById('regenerateBtn').innerHTML = '🔄 Regenerate Questions';
    }

    // Render questions
    function renderQuestions(questions) {
        const container = document.getElementById('questionPreview');
        container.innerHTML = '';

        questions.forEach((q, index) => {
            const card = document.createElement('div');
            card.className = 'bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all';
            card.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs text-gray-400">Q${q.question_number}</span>
                        <p class="text-lg font-semibold text-[#5C3A1E] mt-1">${q.display}</p>
                        <p class="text-sm text-gray-500 mt-1">Answer: <span class="font-bold text-green-600">${q.answer_display}</span></p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full 
                        ${q.type === 'two_numbers' ? 'bg-blue-100 text-blue-700' : 
                          (q.type === 'three_numbers' ? 'bg-purple-100 text-purple-700' : 
                           'bg-orange-100 text-orange-700')}">
                        ${q.type.replace('_', ' ')}
                    </span>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Regenerate questions
    document.getElementById('regenerateBtn').addEventListener('click', generateQuestions);

    // Auto-generate on setting change with debounce
    let debounceTimer;
    document.querySelectorAll('#questionForm input, #questionForm select').forEach(element => {
        element.addEventListener('change', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                if (this.id !== 'title' && this.id !== 'description' && this.id !== 'time_limit' && this.id !== 'is_public') {
                    generateQuestions();
                }
            }, 500);
        });
    });

    // Initial generate on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(generateQuestions, 300);
    });

    // Form submission
    document.getElementById('questionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (currentQuestions.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Questions',
                text: 'Please generate questions first before saving.',
                confirmButtonColor: '#5C3A1E'
            });
            return;
        }

        // Show loading
        Swal.fire({
            title: '⏳ Saving Question Set...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Build the data object
        const data = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value,
            number_of_questions: parseInt(document.getElementById('number_of_questions').value),
            time_limit: document.getElementById('time_limit').value ? parseInt(document.getElementById('time_limit').value) : null,
            is_public: document.getElementById('is_public').checked ? true : false,
            min_value: parseInt(document.getElementById('min_value').value),
            max_value: parseInt(document.getElementById('max_value').value),
            questions: currentQuestions
        };

        console.log('Sending data:', data);

        fetch('{{ route("teacher.questions.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '✅ Question Set Created!',
                    text: data.message,
                    confirmButtonColor: '#5C3A1E'
                }).then(() => {
                    window.location.href = `/teacher/questions/${data.question_set_id}`;
                });
            } else {
                let errorMessage = data.error || 'Failed to create question set';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMessage = errors.join('\n');
                }
                Swal.fire({
                    icon: 'error',
                    title: '❌ Error',
                    text: errorMessage,
                    confirmButtonColor: '#5C3A1E'
                });
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: '❌ Error',
                text: 'Something went wrong. Please try again.',
                confirmButtonColor: '#5C3A1E'
            });
        });
    });

    // Make functions globally accessible
    window.generateQuestions = generateQuestions;
</script>
@endsection