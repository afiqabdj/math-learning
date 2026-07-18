<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quest - Learn & Play</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700;900&family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Quicksand', 'Comic Neue', cursive;
        }
        
        .page-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            overflow: hidden;
        }
        
        .page-background video {
            position: absolute;
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
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
        
        input:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <!-- Video Background -->
    <div class="page-background">
        <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
            <source src="{{ asset('videos/sky-bg.mp4') }}" type="video/mp4">
            <div class="absolute inset-0 bg-gradient-to-b from-[#87CEEB] to-[#B0E0E6]"></div>
        </video>
        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    <!-- Transition Overlay -->
    <div id="transitionOverlay" class="transition-overlay">
        <div class="text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-3xl font-bold text-white">Loading... 🌟</p>
        </div>
    </div>

    <!-- Main Content (No Navigation Bar) -->
    <main>
        @yield('content')
    </main>

    <script>
        function navigateWithTransition(url) {
            const overlay = document.getElementById('transitionOverlay');
            overlay.classList.add('active');
            
            setTimeout(() => {
                window.location.href = url;
            }, 400);
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('transitionOverlay');
            if (overlay) {
                overlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>