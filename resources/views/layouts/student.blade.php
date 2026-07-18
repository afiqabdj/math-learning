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

</body>

</html>