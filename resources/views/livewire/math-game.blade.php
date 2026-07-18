<div class="bg-white/95 backdrop-blur-sm rounded-3xl border-8 border-yellow-400 p-8 shadow-2xl">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-6">
        <!-- Lesson Name -->
        <div class="bg-gradient-to-r from-blue-200 to-purple-200 rounded-full px-8 py-4 border-4 border-white shadow-lg">
            <span class="text-3xl font-bold">{{ $lessonName }}</span>
        </div>
        
        <!-- Stars and Lives -->
        <div class="flex space-x-4">
            <div class="bg-purple-200 rounded-full px-8 py-4 border-4 border-white shadow-lg">
                <span class="text-3xl">⭐ <span class="font-bold">{{ $score }}</span></span>
            </div>
            <div class="bg-red-200 rounded-full px-6 py-4 border-4 border-white shadow-lg">
                @for($i = 0; $i < $lives; $i++)
                    <span class="text-4xl">❤️</span>
                @endfor
                @for($i = $lives; $i < 3; $i++)
                    <span class="text-4xl">🖤</span>
                @endfor
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="flex justify-between text-2xl mb-2">
            <span>Question {{ $currentQuestion }} of {{ $totalQuestions }}</span>
            <span>{{ round(($currentQuestion-1)/$totalQuestions*100) }}% done</span>
        </div>
        <div class="bg-gray-200 rounded-full h-6">
            <div class="bg-green-500 rounded-full h-6 transition-all duration-500" 
                 style="width: {{ ($currentQuestion-1)/$totalQuestions*100 }}%"></div>
        </div>
    </div>

    <!-- Main Game Area -->
    <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-3xl p-12 mb-6 text-center border-4 border-white shadow-inner">
        @if($lessonComplete)
            <!-- Lesson Complete! -->
            <div class="text-center py-8">
                <span class="text-9xl mb-6 block animate-bounce">🎉</span>
                <h2 class="text-7xl font-bold text-green-500 mb-4">Great Job!</h2>
                <p class="text-4xl mb-4">You finished {{ $lessonName }}!</p>
                <p class="text-5xl font-bold text-purple-600 mb-8">⭐ {{ $score }} Stars ⭐</p>
                
                @if($lessonId == 1)
                    <button 
                        wire:click="nextLesson"
                        class="bg-gradient-to-r from-blue-400 to-purple-400 text-white px-12 py-6 rounded-full font-bold text-4xl shadow-xl hover:scale-110 transition-transform border-4 border-white"
                    >
                        Next Lesson 2 ➡️
                    </button>
                @elseif($lessonId == 2)
                    <div class="space-y-4">
                        <p class="text-3xl text-gray-600">Ready for more?</p>
                        <button class="bg-green-400 text-white px-12 py-6 rounded-full font-bold text-4xl shadow-xl hover:scale-110 transition-transform border-4 border-white">
                            Lesson 3 (Coming Soon!)
                        </button>
                    </div>
                @endif
            </div>
            
        @elseif($gameOver)
            <!-- Game Over but encouraging -->
            <div class="text-center py-8">
                <span class="text-8xl mb-4 block">😊</span>
                <h2 class="text-6xl font-bold text-orange-500 mb-4">Good Try!</h2>
                <p class="text-4xl mb-4">You got {{ $score }} stars!</p>
                <p class="text-3xl text-gray-600 mb-8">Want to try {{ $lessonName }} again?</p>
                <button 
                    wire:click="resetGame"
                    class="bg-gradient-to-r from-green-400 to-blue-400 text-white px-12 py-6 rounded-full font-bold text-4xl shadow-xl hover:scale-110 transition-transform border-4 border-white"
                >
                    🔄 Try Again
                </button>
            </div>
            
        @elseif($lessonId == 1)
            <!-- Lesson 1: Addition to 10 -->
            <div class="mb-12">
                <div class="text-9xl font-bold mb-8">
                    <span class="text-blue-500">{{ $num1 }}</span>
                    <span class="text-purple-500 mx-4">+</span>
                    <span class="text-green-500">{{ $num2 }}</span>
                    <span class="text-orange-500 mx-4">=</span>
                    <span class="text-red-500">?</span>
                </div>
               
            </div>

            <!-- Answer Choices -->
            <div class="grid grid-cols-2 gap-6 max-w-2xl mx-auto">
                @foreach($options as $index => $option)
                    <button 
                        wire:click="checkAnswer({{ $option }})"
                        class="bg-gradient-to-br 
                            @if($index == 0) from-red-300 to-red-400
                            @elseif($index == 1) from-blue-300 to-blue-400
                            @elseif($index == 2) from-green-300 to-green-400
                            @else from-yellow-300 to-yellow-400
                            @endif
                            text-white text-6xl font-bold p-8 rounded-3xl hover:scale-110 transition-transform border-4 border-white shadow-xl"
                    >
                        {{ $option }}
                    </button>
                @endforeach
            </div>
            
        @elseif($lessonId == 2)
            <!-- Lesson 2: Find the missing number -->
            <div class="mb-12">
                <div class="text-9xl font-bold mb-8">
                    <span class="text-blue-500">___</span>
                    <span class="text-purple-500 mx-4">+</span>
                    <span class="text-green-500">{{ $num1 }}</span>
                    <span class="text-orange-500 mx-4">=</span>
                    <span class="text-red-500">10</span>
                </div>
                <p class="text-3xl text-gray-500 mb-8">What number is missing?</p>
            </div>

            <!-- Answer Choices -->
            <div class="grid grid-cols-2 gap-6 max-w-2xl mx-auto">
                @foreach($options as $index => $option)
                    <button 
                        wire:click="checkAnswer({{ $option }})"
                        class="bg-gradient-to-br 
                            @if($index == 0) from-red-300 to-red-400
                            @elseif($index == 1) from-blue-300 to-blue-400
                            @elseif($index == 2) from-green-300 to-green-400
                            @else from-yellow-300 to-yellow-400
                            @endif
                            text-white text-6xl font-bold p-8 rounded-3xl hover:scale-110 transition-transform border-4 border-white shadow-xl"
                    >
                        {{ $option }}
                    </button>
                @endforeach
            </div>
        @endif

        <!-- Feedback Message -->
        @if($message && !$lessonComplete && !$gameOver)
            <div class="mt-8 text-4xl font-bold 
                @if($messageType == 'success') text-green-600
                @else text-red-600
                @endif">
                {{ $message }}
            </div>
        @endif
    </div>

    <!-- Simple Tips -->
    <div class="bg-yellow-100 rounded-2xl p-6 border-4 border-yellow-300">
        <div class="flex items-center">
            <span class="text-5xl mr-4">💡</span>
            <p class="text-3xl text-gray-700">
                @if($lessonId == 1)
                    Complete the equation
                @elseif($lessonId == 2)
                    Tip: How many more to make 10? 10 - {{ $num1 ?? 5 }} = ?
                @else
                    You're doing great!
                @endif
            </p>
        </div>
    </div>
</div>