@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="text-7xl mb-4 drop-shadow-lg">🧮</div>
            <h2 class="text-4xl font-bold text-white drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                Math Quest
            </h2>
            <p class="mt-2 text-lg text-white drop-shadow" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                Sign in to continue your adventure!
            </p>
        </div>

        <!-- Login Form - SOLID WHITE CARD -->
        <div class="bg-white rounded-2xl p-8 shadow-2xl border border-gray-200">
            <form id="loginForm" class="space-y-6">
                @csrf
                
                <!-- Role Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        I am a:
                    </label>
                    <div class="flex gap-6">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="role" value="student" checked
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="text-gray-700 font-medium text-lg">🧑‍🎓 Student</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="radio" name="role" value="teacher"
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="text-gray-700 font-medium text-lg">👩‍🏫 Teacher</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-800 text-lg"
                        placeholder="Enter your username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-800 text-lg"
                        placeholder="Enter your password">
                </div>

              

                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl font-bold text-lg text-white transition-all transform hover:scale-105 shadow-lg"
                    style="background: linear-gradient(135deg, #4A90D9, #357ABD);">
                    Sign In 🚀
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Don't have an account?
                    <a href="/signup" class="text-blue-600 hover:text-blue-700 font-semibold transition">
                        Sign up
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center">
            <span class="text-2xl">✨</span>
            <span class="text-2xl mx-2">⭐</span>
            <span class="text-2xl">✨</span>
        </div>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get selected role
        const selectedRole = document.querySelector('input[name="role"]:checked');
        const role = selectedRole ? selectedRole.value : 'student';
        
        const formData = new FormData(this);
        // Make sure role is included in the request
        formData.set('role', role);
        
        // Log for debugging
        console.log('Selected role:', role);
        console.log('Username:', document.getElementById('username').value);
        
        fetch('/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                const overlay = document.getElementById('transitionOverlay');
                if (overlay) overlay.classList.add('active');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 500);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
        });
    });
</script>
@endsection