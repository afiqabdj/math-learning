<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Quest - Learn & Play</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700;900&family=Quicksand:wght@400;600;700&display=swap"
        rel="stylesheet">
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Video Background -->
    <div class="page-background">
        <video autoplay loop muted playsinline class="absolute min-w-full min-h-full object-cover">
            <source src="{{ asset('videos/nature-math-bg.mp4') }}" type="video/mp4">
            <div class="absolute inset-0 bg-gradient-to-b from-[#87CEEB] via-[#98FB98] to-[#F4A623]"></div>
        </video>
        <div class="absolute inset-0 bg-black/10"></div>
    </div>

    <!-- Navigation Bar - Only visible on authenticated pages -->
    <nav class="bg-white/90 backdrop-blur-sm shadow-lg sticky top-0 z-50 border-b border-white/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">🧮</span>
                    <a href="/dashboard" class="text-xl font-bold" style="color: #5A6B2D;">Math Quest</a>
                </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-100 px-3 py-1 rounded-full">
                        <span class="text-sm font-bold text-yellow-600" id="navUsername">Guest</span>
                    </div>
                    <a href="/logout"
                        class="bg-red-500/80 hover:bg-red-600 text-white px-3 py-1 rounded-full text-sm transition">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Transition Overlay -->
    <div id="transitionOverlay" class="transition-overlay">
        <div class="text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-3xl font-bold text-white">Loading... 🌟</p>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <script>
        // Load username from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const currentUser = localStorage.getItem('currentUser');
            if (currentUser) {
                document.getElementById('navUsername').innerHTML = currentUser;
            }
        });

        // Page transition function
        function navigateWithTransition(url, buttonElement) {
            if (buttonElement) {
                buttonElement.classList.add('btn-click-effect');
                setTimeout(() => {
                    buttonElement.classList.remove('btn-click-effect');
                }, 400);
            }

            const overlay = document.getElementById('transitionOverlay');
            overlay.classList.add('active');

            setTimeout(() => {
                window.location.href = url;
            }, 400);
        }

        // Add page load animation
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('transitionOverlay');
            if (overlay) {
                overlay.classList.remove('active');
            }

            const content = document.querySelector('main');
            if (content) {
                content.style.animation = 'zoomIn 0.5s ease-out';
            }
        });

        // Get authenticated user info
        fetch('/api/user')
            .then(response => response.json())
            .then(user => {
                if (user.username) {
                    document.getElementById('navUsername').innerHTML = user.username;
                }
            })
            .catch(error => console.log('Not logged in'));

        // Load username from session
        document.addEventListener('DOMContentLoaded', function() {
            const usernameElement = document.getElementById('navUsername');
            if (usernameElement && usernameElement.innerHTML === 'Guest') {
                // Try to get from meta tag or leave as Guest
            }
        });
    </script>

    <style>
        @keyframes zoomIn {
            0% {
                transform: scale(0.95);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes btnClick {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.95);
            }

            100% {
                transform: scale(1);
            }
        }

        .btn-click-effect {
            animation: btnClick 0.3s ease-in-out;
        }
    </style>
</body>

</html>
