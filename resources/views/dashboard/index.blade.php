@extends('layouts.app')

@section('content')
<!-- Transition Overlay -->
<div id="transitionOverlay" class="transition-overlay">
    <div class="text-center">
        <div class="loading-spinner mx-auto mb-4"></div>
        <p class="text-3xl font-bold" style="color: #E6D07A;">Loading... 🌟</p>
    </div>
</div>

<!-- Video Background -->
<div class="fixed inset-0 w-full h-full -z-10 overflow-hidden">
    <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
        <source src="{{ asset('videos/nature-math-bg.mp4') }}" type="video/mp4">
        <div class="absolute inset-0 bg-gradient-to-b from-[#9FC5CF] via-[#BFD8D2] to-[#7A8B3B]"></div>
    </video>
    <div class="absolute inset-0 bg-black/10"></div>
</div>

<div class="relative z-10 min-h-screen py-12 px-6">
    
    <!-- Welcome Text -->
    <div class="text-center mb-16 animate-fadeIn">
        <h1 class="text-6xl md:text-7xl font-bold mb-3" style="color: #EDEDED; text-shadow: 2px 2px 8px rgba(0,0,0,0.2);">
            Welcome to Math Quest! 
        </h1>
        <p class="text-2xl md:text-3xl" style="color: #F4A623; font-weight: bold; text-shadow: 1px 1px 4px rgba(0,0,0,0.1);">
            Let's explore math in nature!
        </p>
    </div>

    <!-- Two Main Buttons - Side by Side -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <!-- Learn Button -->
        <a href="/lessons" 
           id="learnBtn"
           class="group relative overflow-hidden rounded-3xl shadow-2xl transform transition-all duration-500 hover:scale-105"
           style="background: linear-gradient(135deg, #7A8B3B 0%, #556B2F 100%); border: 3px solid #E6D07A;">
            
            <div class="absolute top-4 right-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">🌸</div>
            <div class="absolute bottom-4 left-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">🌼</div>
            
            <div class="relative py-12 px-6 text-center">
                <span class="text-8xl block mb-4 drop-shadow-lg">📚</span>
                <h2 class="text-5xl font-bold mb-3" style="color: #E6D07A; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    LEARN
                </h2>
                <p class="text-xl mb-4" style="color: #EDEDED;">
                    Follow the learning trail 🛤️
                </p>
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full border border-white/30">
                    <span style="color: #E6D07A;">→</span>
                    <span style="color: #EDEDED;">Start your journey</span>
                </div>
            </div>
        </a>

        <!-- Play Button -->
        <a href="/game/levels" 
           id="playBtn"
           class="group relative overflow-hidden rounded-3xl shadow-2xl transform transition-all duration-500 hover:scale-105"
           style="background: linear-gradient(135deg, #8C9DB3 0%, #7D8FA8 100%); border: 3px solid #F4A623;">
            
            <div class="absolute top-4 right-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">☀️</div>
            <div class="absolute bottom-4 left-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">⛅</div>
            
            <div class="relative py-12 px-6 text-center">
                <span class="text-8xl block mb-4 drop-shadow-lg animate-float">🎮</span>
                <h2 class="text-5xl font-bold mb-3" style="color: #F4A623; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    PLAY
                </h2>
                <p class="text-xl mb-4" style="color: #EDEDED;">
                    Run through the meadow 🌿
                </p>
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full border border-white/30">
                    <span style="color: #F4A623;">🎯</span>
                    <span style="color: #EDEDED;">Start playing</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Third Row - Two More Buttons (Performance & Assignments) -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        
        <!-- Performance Button -->
        <a href="/performance" 
           id="performanceBtn"
           class="group relative overflow-hidden rounded-3xl shadow-2xl transform transition-all duration-500 hover:scale-105"
           style="background: linear-gradient(135deg, #9B59B6 0%, #7D3C98 100%); border: 3px solid #E6D07A;">
            
            <div class="absolute top-4 right-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">📊</div>
            <div class="absolute bottom-4 left-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">🏆</div>
            
            <div class="relative py-10 px-6 text-center">
                <span class="text-7xl block mb-3 drop-shadow-lg">📊</span>
                <h2 class="text-4xl font-bold mb-2" style="color: #E6D07A; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    PERFORMANCE
                </h2>
                <p class="text-lg mb-3" style="color: #EDEDED;">
                    Track your progress 📈
                </p>
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-5 py-2 rounded-full border border-white/30">
                    <span style="color: #E6D07A;">📈</span>
                    <span style="color: #EDEDED;">View your stats</span>
                </div>
            </div>
        </a>

        <!-- Assignments Button -->
        <a href="{{ route('student.assignments.index') }}" 
           id="assignmentsBtn"
           class="group relative overflow-hidden rounded-3xl shadow-2xl transform transition-all duration-500 hover:scale-105"
           style="background: linear-gradient(135deg, #2ECC71 0%, #27AE60 100%); border: 3px solid #E6D07A;">
            
            <div class="absolute top-4 right-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">📝</div>
            <div class="absolute bottom-4 left-4 text-3xl opacity-50 group-hover:opacity-100 transition-opacity">✅</div>
            
            <div class="relative py-10 px-6 text-center">
                <span class="text-7xl block mb-3 drop-shadow-lg">📝</span>
                <h2 class="text-4xl font-bold mb-2" style="color: #E6D07A; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">
                    ASSIGNMENTS
                </h2>
                <p class="text-lg mb-3" style="color: #EDEDED;">
                    Enroll and Complete your tasks 🎯
                </p>
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-5 py-2 rounded-full border border-white/30">
                    <span style="color: #E6D07A;">📋</span>
                    <span style="color: #EDEDED;">View assignments</span>
                </div>
            </div>
        </a>
    </div>
</div>

<script>
    // Page transition function
    function navigateWithTransition(url, buttonElement) {
        // Add click animation to button
        if (buttonElement) {
            buttonElement.classList.add('btn-click-effect');
            setTimeout(() => {
                buttonElement.classList.remove('btn-click-effect');
            }, 400);
        }
        
        // Show transition overlay
        const overlay = document.getElementById('transitionOverlay');
        overlay.classList.add('active');
        
        // Zoom out effect on content
        const content = document.querySelector('.relative.z-10');
        if (content) {
            content.style.animation = 'zoomOut 0.4s ease-out';
        }
        
        // Navigate after animation
        setTimeout(() => {
            window.location.href = url;
        }, 400);
    }
    
    // Add click handlers to buttons
    document.getElementById('learnBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        navigateWithTransition(this.getAttribute('href'), this);
    });
    
    document.getElementById('playBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        navigateWithTransition(this.getAttribute('href'), this);
    });
    
    document.getElementById('performanceBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        navigateWithTransition(this.getAttribute('href'), this);
    });
    
    document.getElementById('assignmentsBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        navigateWithTransition(this.getAttribute('href'), this);
    });
    
    // Add page load animation
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('transitionOverlay');
        if (overlay) {
            overlay.classList.remove('active');
        }
        
        const content = document.querySelector('.relative.z-10');
        if (content) {
            content.style.animation = 'zoomIn 0.5s ease-out';
        }
    });
</script>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes zoomIn {
        0% { transform: scale(0.95); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes zoomOut {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0; }
    }
    @keyframes btnClick {
        0% { transform: scale(1); }
        50% { transform: scale(0.95); }
        100% { transform: scale(1); }
    }
    
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out;
    }
    .btn-click-effect {
        animation: btnClick 0.3s ease-in-out;
    }
    
    .transition-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    .transition-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(230, 208, 122, 0.3);
        border-top: 4px solid #E6D07A;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection