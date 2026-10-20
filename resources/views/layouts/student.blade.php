<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Math Quest - Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #5D4037;
        }

        .student-page {
            background: linear-gradient(135deg, #1a3a5c 0%, #2a5a7a 50%, #3a7a9a 100%);
            min-height: 100vh;
            position: relative;
        }

        .student-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .student-page>* {
            position: relative;
            z-index: 1;
        }

        .student-header {
            background: white;
            border-bottom: 3px solid #4a9aba;
            padding: 16px 24px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .student-header .title {
            color: #5D4037;
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .student-header .title .emoji {
            font-size: 2rem;
            margin-right: 10px;
        }

        .student-header .title .highlight {
            color: #4a9aba;
        }

        .student-header .subtitle {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
            margin-top: 2px;
            letter-spacing: 2px;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #E8E0D8;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #4a9aba;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #3a8aaa;
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

        /* ============================================
           MUSIC PLAYER BUTTON STYLES
           ============================================ */
        .music-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: white;
            border: 2px solid #4a9aba;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #5D4037;
        }

        .music-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(74, 154, 186, 0.4);
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
            z-index: 9999;
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
            color: #5D4037;
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

<body class="student-page">

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
        <source src="{{ asset('assets/audio/button.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/button.ogg') }}" type="audio/ogg">
    </audio>

    <audio id="successSound" preload="auto">
        <source src="{{ asset('assets/audio/correct.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/correct.ogg') }}" type="audio/ogg">
    </audio>

    <audio id="errorSound" preload="auto">
        <source src="{{ asset('assets/audio/error.mp3') }}" type="audio/mpeg">
        <source src="{{ asset('assets/audio/error.ogg') }}" type="audio/ogg">
    </audio>

    <!-- Sound Effects Toggle Button -->
    <button id="soundToggle" class="sound-toggle-btn on" title="Toggle Sound Effects">
        🔊
    </button>

    <!-- Header -->
    <header class="student-header">
        <div class="title">
            <span class="emoji">🧮</span>
            <a href="/dashboard">Math Quest</a> <span class="highlight">Student</span>
        </div>
        <div class="subtitle">Learn and practice math</div>
    </header>

    <!-- Main Content -->
    <main class="fade-in">
        @yield('content')
    </main>

    <!-- ============================================
         JAVASCRIPT - ALL FEATURES
         ============================================ -->
    <script>
        (function() {
            'use strict';

            // ========================================
            // 1. BACKGROUND MUSIC
            // ========================================
            const audio = document.getElementById('bgMusic');
            const toggleBtn = document.getElementById('musicToggle');

            // Restore state from localStorage
            const wasPlaying = localStorage.getItem('bgMusicPlaying') === 'true';
            const savedTime = parseFloat(localStorage.getItem('bgMusicTime')) || 0;

            // Set volume (default 50%)
            audio.volume = 0.5;

            // Restore position
            if (savedTime > 0) {
                audio.currentTime = savedTime;
            }

            // Restore playing state
            if (wasPlaying) {
                audio.play().then(() => {
                    toggleBtn.textContent = '🔊';
                    toggleBtn.className = 'music-btn playing';
                }).catch(function(error) {
                    console.log('Auto-play blocked. User must interact.');
                    localStorage.setItem('bgMusicPlaying', 'false');
                    toggleBtn.textContent = '🔇';
                    toggleBtn.className = 'music-btn muted';
                });
            } else {
                toggleBtn.textContent = '🔇';
                toggleBtn.className = 'music-btn muted';
            }

            // Toggle music
            toggleBtn.addEventListener('click', function() {
                if (audio.paused) {
                    audio.play().then(() => {
                        toggleBtn.textContent = '🔊';
                        toggleBtn.className = 'music-btn playing';
                        localStorage.setItem('bgMusicPlaying', 'true');
                    }).catch(function(error) {
                        console.log('Play error:', error);
                    });
                } else {
                    audio.pause();
                    toggleBtn.textContent = '🔇';
                    toggleBtn.className = 'music-btn muted';
                    localStorage.setItem('bgMusicPlaying', 'false');
                }
            });

            // Save music state before page unload
            window.addEventListener('beforeunload', function() {
                localStorage.setItem('bgMusicTime', audio.currentTime);
                localStorage.setItem('bgMusicPlaying', !audio.paused);
            });

            // Save periodically (every 2 seconds)
            setInterval(function() {
                if (!audio.paused) {
                    localStorage.setItem('bgMusicTime', audio.currentTime);
                    localStorage.setItem('bgMusicPlaying', 'true');
                }
            }, 2000);

            // Handle visibility change
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    // Tab hidden - you might want to pause (optional)
                    // if (!audio.paused) { audio.pause(); }
                } else {
                    // Tab visible again - resume if it was playing
                    if (localStorage.getItem('bgMusicPlaying') === 'true') {
                        audio.play().catch(function() {});
                    }
                }
            });

            // Play on user interaction if needed
            document.addEventListener('click', function() {
                if (localStorage.getItem('bgMusicPlaying') === 'true' && audio.paused) {
                    audio.play().catch(function() {});
                }
            }, { once: false });

            // ========================================
            // 2. SOUND EFFECTS
            // ========================================
            let soundEnabled = localStorage.getItem('soundEnabled') !== 'false';
            const soundToggle = document.getElementById('soundToggle');

            // Update sound toggle button
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

            // Toggle sound effects
            soundToggle.addEventListener('click', function() {
                soundEnabled = !soundEnabled;
                updateSoundToggle();
            });

            // Play sound function
            function playSound(type) {
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
            }

            // ========================================
            // 3. ATTACH SOUND TO A SINGLE ELEMENT
            // ========================================
            function attachSoundToElement(element, soundType) {
                if (!element) return;
                if (element.dataset.soundAttached) return;
                element.dataset.soundAttached = 'true';
                
                element.addEventListener('click', function(e) {
                    if (this.disabled) return;
                    const type = this.dataset.sound || soundType || 'click';
                    playSound(type);
                });
            }

            // ========================================
            // 4. AUTO-ATTACH SOUND TO BUTTONS & LINKS
            // ========================================
            document.addEventListener('DOMContentLoaded', function() {
                // Attach to all buttons and links (except those with 'no-sound' class)
                document.querySelectorAll('button:not(.no-sound), a:not(.no-sound)').forEach(function(element) {
                    attachSoundToElement(element);
                });

                // Also attach to elements with 'with-sound' class
                document.querySelectorAll('.with-sound').forEach(function(element) {
                    attachSoundToElement(element);
                });

                console.log('Sound system initialized. Sound enabled:', soundEnabled);
            });

            // ========================================
            // 5. HELPER FUNCTIONS FOR SOUND EVENTS
            // ========================================
            window.triggerCorrect = function(source) {
                if (window.playSound) {
                    window.playSound('success');
                    console.log('🎵 Correct! (' + (source || 'unknown') + ')');
                }
            };

            window.triggerWrong = function(source) {
                if (window.playSound) {
                    window.playSound('error');
                    console.log('🎵 Wrong! (' + (source || 'unknown') + ')');
                }
            };

            window.triggerComplete = function(source) {
                if (window.playSound) {
                    window.playSound('success');
                    console.log('🎵 Complete! (' + (source || 'unknown') + ')');
                }
            };

            // ========================================
            // 6. EXPOSE FUNCTIONS GLOBALLY
            // ========================================
            window.playSound = playSound;
            window.soundEnabled = soundEnabled;
            window.attachSoundToElement = attachSoundToElement;

        })();
    </script>

</body>

</html>