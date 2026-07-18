@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="text-7xl mb-4 drop-shadow-lg">🧮</div>
            <h2 class="text-4xl font-bold text-white drop-shadow-lg" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                Join Math Quest
            </h2>
            <p class="mt-2 text-lg text-white drop-shadow" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">
                Start your math adventure today!
            </p>
        </div>

        <!-- Signup Form - SOLID WHITE CARD -->
        <div class="bg-white rounded-2xl p-8 shadow-2xl border border-gray-200">
            <form id="signupForm" class="space-y-6">
                @csrf
                
                <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input type="text" id="fullname" name="name" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-800 text-lg"
                        placeholder="Enter your full name">
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <input type="text" id="username" name="username" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-800 text-lg"
                        placeholder="Choose a username">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-800 text-lg"
                        placeholder="Create a password">
                    <p class="text-xs text-gray-500 mt-1">Password must be at least 4 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all text-gray-800 text-lg"
                        placeholder="Confirm your password">
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 rounded-xl font-bold text-lg text-white transition-all transform hover:scale-105 shadow-lg"
                    style="background: linear-gradient(135deg, #4A90D9, #357ABD);">
                    Sign Up 🎉
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Already have an account?
                    <a href="/login" class="text-blue-600 hover:text-blue-700 font-semibold transition">
                        Sign in
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center">
            <span class="text-2xl">🌟</span>
            <span class="text-2xl mx-2">✨</span>
            <span class="text-2xl">🌟</span>
        </div>
    </div>
</div>

<script>
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = '⏳ Signing Up...';
        submitBtn.disabled = true;
        
        fetch('/signup', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const overlay = document.getElementById('transitionOverlay');
                if (overlay) overlay.classList.add('active');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 500);
            } else {
                // Show error message
                let errorMsg = data.message || 'Something went wrong. Please try again.';
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    errorMsg = errors.join('\n');
                }
                alert(errorMsg);
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
</script>
@endsection