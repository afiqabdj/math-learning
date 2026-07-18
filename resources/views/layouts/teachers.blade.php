<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Math Quest - Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Using Inter - the most popular font on the planet -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Global font - Inter (used by Google, Meta, Twitter, and millions of sites) */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Brown background for teacher pages */
        .teacher-page {
            background: linear-gradient(135deg, #5C3A1E 0%, #7A4A2A 50%, #8B5A3A 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Subtle pattern overlay */
        .teacher-page::before {
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

        .teacher-page>* {
            position: relative;
            z-index: 1;
        }

        /* Header styles - More modern */
        .teacher-header {
            background: rgba(44, 24, 16, 0.95);
            border-bottom: 3px solid #D4A06A;
            padding: 20px 24px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        .teacher-header .title {
            color: #ffffff;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .teacher-header .title .emoji {
            font-size: 2.2rem;
            margin-right: 12px;
        }

        .teacher-header .title .highlight {
            color: #F4A623;
            background: linear-gradient(to right, #F4A623, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .teacher-header .subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.95rem;
            margin-top: 4px;
            letter-spacing: 3px;
            font-weight: 300;
        }

        /* Card styles - Larger and more modern */
        .teacher-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .teacher-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .teacher-card .card-title {
            color: #5C3A1E;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .teacher-card .card-value {
            color: #5C3A1E;
            font-weight: 900;
            font-size: 2.5rem;
            letter-spacing: -0.5px;
        }

        /* Button styles - Larger and more touch-friendly */
        .btn-brown {
            background: #5C3A1E;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 24px;
            border-radius: 12px;
            letter-spacing: 0.3px;
        }

        .btn-brown:hover {
            background: #7A4A2A;
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(92, 58, 30, 0.4);
        }

        .btn-brown-outline {
            border: 2px solid #5C3A1E;
            color: #5C3A1E;
            background: transparent;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 24px;
            border-radius: 12px;
        }

        .btn-brown-outline:hover {
            background: #5C3A1E;
            color: white;
            transform: scale(1.02);
        }

        /* Table styles - More readable */
        .teacher-table th {
            background: #F5F0EB;
            color: #5C3A1E;
            font-weight: 700;
            padding: 16px 20px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .teacher-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #E8E0D8;
            color: #4A3520;
            font-size: 0.95rem;
        }

        .teacher-table tr:hover td {
            background: #FAF6F0;
        }

        /* Status badges - More visible */
        .badge-complete {
            background: #D4EDDA;
            color: #155724;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .badge-progress {
            background: #FFF3CD;
            color: #856404;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .badge-notstarted {
            background: #E2E3E5;
            color: #383D41;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Filter inputs - Larger */
        .filter-input {
            background: #F5F0EB;
            border: 2px solid #D4C5B8;
            border-radius: 12px;
            padding: 10px 16px;
            color: #5C3A1E;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .filter-input:focus {
            outline: none;
            border-color: #D4A06A;
            box-shadow: 0 0 0 4px rgba(212, 160, 106, 0.2);
        }

        .filter-select {
            background: #F5F0EB;
            border: 2px solid #D4C5B8;
            border-radius: 12px;
            padding: 10px 16px;
            color: #5C3A1E;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .filter-select:focus {
            outline: none;
            border-color: #D4A06A;
            box-shadow: 0 0 0 4px rgba(212, 160, 106, 0.2);
        }

        /* Animation */
        @keyframes zoomIn {
            0% {
                transform: scale(0.95) translateY(10px);
                opacity: 0;
            }
            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .fade-in {
            animation: zoomIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #E8E0D8;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #D4A06A;
            border-radius: 10px;
            border: 2px solid #E8E0D8;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #C4905A;
        }

        /* Sidebar improvements */
        .sidebar-link {
            padding: 14px 18px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-link:hover {
            background: rgba(92, 58, 30, 0.08);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: #5C3A1E;
            color: white;
            box-shadow: 0 4px 15px rgba(92, 58, 30, 0.3);
        }

        .sidebar-link .icon {
            font-size: 1.5rem;
            width: 32px;
            text-align: center;
        }

        /* Form improvements */
        .form-input {
            border: 2px solid #E8E0D8;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s;
            width: 100%;
        }

        .form-input:focus {
            outline: none;
            border-color: #5C3A1E;
            box-shadow: 0 0 0 4px rgba(92, 58, 30, 0.1);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.95rem;
            color: #4A3520;
            display: block;
            margin-bottom: 6px;
        }

        /* Stats card icons */
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .teacher-header .title {
                font-size: 1.5rem;
            }
            
            .teacher-card .card-value {
                font-size: 2rem;
            }
            
            .sidebar-link {
                padding: 12px 14px;
                font-size: 0.95rem;
            }
        }

        /* Loading animation */
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #5C3A1E;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Hover effects for table rows */
        .clickable-row {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .clickable-row:hover {
            background: #FAF6F0 !important;
            transform: scale(1.002);
        }
    </style>
</head>

<body class="teacher-page">

    <!-- Modern Header -->
    <header class="teacher-header">
        <div class="title">
            <span class="emoji">👩‍🏫</span>
            Math Quest <span class="highlight">Teacher</span>
        </div>
        <div class="subtitle">School Name</div>
    </header>

    <!-- Main Content -->
    <main class="fade-in">
        @yield('content')
    </main>

    <script>
        // Load username
        document.addEventListener('DOMContentLoaded', function() {
            const currentUser = localStorage.getItem('currentUser');
            if (currentUser) {
                // You can display username somewhere if needed
            }
        });

        // Get authenticated user info
        fetch('/api/user')
            .then(response => response.json())
            .then(user => {
                if (user.username) {
                    // You can display username somewhere if needed
                }
            })
            .catch(error => console.log('Not logged in'));
    </script>

</body>

</html>