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

        /* ============================================
           MUSIC PLAYER BUTTON STYLES
           ============================================ */
        .music-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9998;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: white;
            border: 3px solid #E6D07A;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #5A6B2D;
        }

        .music-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(230, 208, 122, 0.5);
        }

        .music-btn.playing {
            border-color: #4CAF50;
            background: #E8F5E9;
        }

        .music-btn.muted {
            border-color: #f44336;
            background: #FFEBEE;
        }

        .music-btn .pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.15);
            }
        }

        /* ============================================
           SOUND EFFECT TOGGLE BUTTON
           ============================================ */
        .sound-toggle-btn {
            position: fixed;
            bottom: 90px;
            right: 24px;
            z-index: 9998;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white;
            border: 2px solid #ccc;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #5A6B2D;
        }

        .sound-toggle-btn:hover {
            transform: scale(1.1);
        }

        .sound-toggle-btn.on {
            border-color: #4CAF50;
            background: #E8F5E9;
        }

        .sound-toggle-btn.off {
            border-color: #f44336;
            background: #FFEBEE;
            opacity: 0.7;
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

    <!-- ============================================
         BACKGROUND MUSIC PLAYER
         ============================================ -->
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ asset('assets/audio/Piki.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/background-music.ogg') }}" type="audio/ogg">
        Your browser does not support the audio element.
    </audio>

    <!-- Music Toggle Button -->
    <button id="musicToggle" class="music-btn muted" title="Toggle Background Music">
        🔇
    </button>

    <!-- ============================================
         SOUND EFFECTS
         ============================================ -->
    <audio id="clickSound" preload="auto">
        <source src="{{ asset('assets/audio/click.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/click.ogg') }}" type="audio/ogg">
    </audio>

    <audio id="successSound" preload="auto">
        <source src="{{ asset('assets/audio/success.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/success.ogg') }}" type="audio/ogg">
    </audio>

    <audio id="errorSound" preload="auto">
        <source src="{{ asset('assets/audio/error.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/error.ogg') }}" type="audio/ogg">
    </audio>

    <!-- Sound Effects Toggle Button -->
    <button id="soundToggle" class="sound-toggle-btn on" title="Toggle Sound Effects">
        🔊
    </button>

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

    <!-- ============================================
         JAVASCRIPT - ALL FEATURES (FIXED)
         ============================================ -->
    <script>
        // ========================================
        // 1. BACKGROUND MUSIC
        // ========================================
        (function() {
            const audio = document.getElementById('bgMusic');
            const toggleBtn = document.getElementById('musicToggle');

            const wasPlaying = localStorage.getItem('bgMusicPlaying') === 'true';
            const savedTime = parseFloat(localStorage.getItem('bgMusicTime')) || 0;

            audio.volume = 0.5;

            if (savedTime > 0) {
                audio.currentTime = savedTime;
            }

            if (wasPlaying) {
                audio.play().then(() => {
                    toggleBtn.textContent = '🔊';
                    toggleBtn.className = 'music-btn playing';
                }).catch(function() {
                    localStorage.setItem('bgMusicPlaying', 'false');
                    toggleBtn.textContent = '🔇';
                    toggleBtn.className = 'music-btn muted';
                });
            } else {
                toggleBtn.textContent = '🔇';
                toggleBtn.className = 'music-btn muted';
            }

            toggleBtn.addEventListener('click', function() {
                if (audio.paused) {
                    audio.play().then(() => {
                        toggleBtn.textContent = '🔊';
                        toggleBtn.className = 'music-btn playing';
                        localStorage.setItem('bgMusicPlaying', 'true');
                    }).catch(function() {});
                } else {
                    audio.pause();
                    toggleBtn.textContent = '🔇';
                    toggleBtn.className = 'music-btn muted';
                    localStorage.setItem('bgMusicPlaying', 'false');
                }
            });

            window.addEventListener('beforeunload', function() {
                localStorage.setItem('bgMusicTime', audio.currentTime);
                localStorage.setItem('bgMusicPlaying', !audio.paused);
            });

            setInterval(function() {
                if (!audio.paused) {
                    localStorage.setItem('bgMusicTime', audio.currentTime);
                    localStorage.setItem('bgMusicPlaying', 'true');
                }
            }, 2000);
        })();

        // ========================================
        // 2. SOUND EFFECTS - GLOBALLY ACCESSIBLE
        // ========================================
        let soundEnabled = localStorage.getItem('soundEnabled') !== 'false';
        const soundToggle = document.getElementById('soundToggle');

        function updateSoundToggle() {
            if (soundEnabled) {
                soundToggle.textContent = '🔊';
                soundToggle.className = 'sound-toggle-btn on';
            } else {
                soundToggle.textContent = '🔇';
                soundToggle.className = 'sound-toggle-btn off';
            }
            localStorage.setItem('soundEnabled', soundEnabled);
        }
        updateSoundToggle();

        soundToggle.addEventListener('click', function() {
            soundEnabled = !soundEnabled;
            updateSoundToggle();
        });

        // GLOBAL playSound function - accessible from ANYWHERE
        window.playSound = function(type) {
            if (!soundEnabled) return;

            const audioMap = {
                'click': document.getElementById('clickSound'),
                'success': document.getElementById('successSound'),
                'error': document.getElementById('errorSound'),
            };

            const sound = audioMap[type] || audioMap['click'];
            if (sound) {
                sound.currentTime = 0;
                sound.volume = 0.5;
                sound.play().catch(function() {});
            }
        };

        // Helper functions for easy use
        window.triggerCorrect = function(source) {
            window.playSound('success');
            console.log('🎵 Correct! (' + (source || 'unknown') + ')');
        };

        window.triggerWrong = function(source) {
            window.playSound('error');
            console.log('🎵 Wrong! (' + (source || 'unknown') + ')');
        };

        window.triggerComplete = function(source) {
            window.playSound('success');
            console.log('🎵 Complete! (' + (source || 'unknown') + ')');
        };

        // ========================================
        // 3. AUTO-ATTACH SOUND TO BUTTONS & LINKS
        // ========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Attach to all buttons and links (except those with 'no-sound' class)
            document.querySelectorAll('button:not(.no-sound), a:not(.no-sound)').forEach(function(element) {
                if (element.dataset.soundAttached) return;
                element.dataset.soundAttached = 'true';

                element.addEventListener('click', function(e) {
                    if (this.disabled) return;
                    const soundType = this.dataset.sound || 'click';
                    window.playSound(soundType);
                });
            });

            // Also attach to elements with 'with-sound' class
            document.querySelectorAll('.with-sound').forEach(function(element) {
                if (element.dataset.soundAttached) return;
                element.dataset.soundAttached = 'true';

                element.addEventListener('click', function() {
                    if (this.disabled) return;
                    const soundType = this.dataset.sound || 'click';
                    window.playSound(soundType);
                });
            });

            console.log('Sound system initialized. Sound enabled:', soundEnabled);
        });

        // ========================================
        // 4. LOAD USERNAME
        // ========================================
        document.addEventListener('DOMContentLoaded', function() {
            const currentUser = localStorage.getItem('currentUser');
            if (currentUser) {
                document.getElementById('navUsername').innerHTML = currentUser;
            }
        });

        // ========================================
        // 5. PAGE TRANSITION FUNCTION
        // ========================================
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

        // ========================================
        // 6. PAGE LOAD ANIMATION
        // ========================================
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

        // ========================================
        // 7. GET AUTHENTICATED USER INFO
        // ========================================
        fetch('/api/user')
            .then(response => response.json())
            .then(user => {
                if (user.username) {
                    document.getElementById('navUsername').innerHTML = user.username;
                }
            })
            .catch(function() {});
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