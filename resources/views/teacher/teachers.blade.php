@extends('layouts.teachers')

@section('content')
    <div class="relative z-10 min-h-screen flex">

        <!-- Left Sidebar Navigation - White -->
        <div class="w-64 bg-white shadow-2xl flex-shrink-0 hidden md:block">
            <div class="p-6">
                <div class="text-center mb-8">
                    <div class="text-5xl mb-2">👩‍🏫</div>
                    <h2 class="text-[#5C3A1E] font-bold text-xl">Teacher Portal</h2>
                    <p class="text-gray-500 text-sm">Math Quest Academy</p>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">📊</span>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                     <a href="{{ route('teacher.questions.index') }}"
                        class="flex items-center space-x-3 {{ request()->routeIs('teacher.questions.*') ? 'bg-[#5C3A1E] text-white' : 'text-gray-600 hover:bg-[#5C3A1E]/10' }} px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">📝</span>
                        <span class="font-semibold">Assignments</span>
                    </a>
                    <a href="{{ route('teacher.students') }}" class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">👨‍🎓</span>
                        <span class="font-semibold">Students</span>
                    </a>
                    <a href="{{ route('teacher.teachers') }}" class="flex items-center space-x-3 bg-[#5C3A1E] text-white px-4 py-3 rounded-xl transition-all shadow-md">
                        <span class="text-2xl">👨‍🏫</span>
                        <span class="font-semibold">Teachers</span>
                    </a>
                </nav>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-[#5C3A1E]/20 flex items-center justify-center text-2xl">
                            👩
                        </div>
                        <div>
                            <p class="text-[#5C3A1E] font-semibold text-sm">{{ Auth::user()->name ?? 'Teacher' }}</p>
                            <p class="text-gray-400 text-xs">Online</p>
                        </div>
                    </div>
                    <a href="/logout" class="mt-4 flex items-center space-x-2 text-gray-400 hover:text-[#5C3A1E] text-sm transition-all">
                        <span>🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white shadow-2xl border-t border-gray-200 z-50">
            <div class="flex justify-around py-3">
                <a href="#" class="text-gray-400 text-2xl">📊</a>
                <a href="#" class="text-gray-400 text-2xl">👨‍🎓</a>
                <a href="#" class="text-[#5C3A1E] text-2xl">👨‍🏫</a>
                <a href="#" class="text-gray-400 text-2xl">📈</a>
                <a href="#" class="text-gray-400 text-2xl">🏆</a>
                <a href="#" class="text-gray-400 text-2xl">⚙️</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 min-h-screen pb-20 md:pb-8">
            <div class="p-4 md:p-8">

                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white flex items-center gap-3">
                                <span>👨‍🏫</span>
                                <span>Teacher Management</span>
                            </h1>
                            <p class="text-white/70 text-sm mt-1">Register and manage teacher accounts</p>
                        </div>
                        <div class="flex items-center gap-3 mt-4 md:mt-0">
                            <button onclick="location.reload()" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm transition-all flex items-center gap-2">
                                <span>🔄</span> Refresh
                            </button>
                            <a href="{{ route('teacher.dashboard') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full text-sm transition-all flex items-center gap-2">
                                <span>📊</span> Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="bg-white rounded-2xl p-6 shadow-xl mb-8">
                    <h3 class="text-[#5C3A1E] font-bold text-lg mb-4">📝 Register New Teacher</h3>
                    <form id="teacherRegistrationForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                            <input type="text" id="regUsername" placeholder="Enter username..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C3A1E]">
                            <p id="regUsernameError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" id="regName" placeholder="Enter full name..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C3A1E]">
                            <p id="regNameError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="regPassword" placeholder="Enter password (min 6 chars)..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#5C3A1E]">
                            <p id="regPasswordError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                        <div class="md:col-span-2 flex items-center gap-4">
                            <button type="submit" class="px-6 py-2 bg-[#5C3A1E] hover:bg-[#7A4A2A] text-white rounded-lg font-bold transition-all">
                                👨‍🏫 Register Teacher
                            </button>
                            <button type="reset" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-all">
                                Clear
                            </button>
                        </div>
                    </form>
                    <div id="registrationMessage" class="mt-4 hidden"></div>
                </div>

                <!-- Teachers List -->
                <div class="bg-white rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#5C3A1E] font-bold text-lg">📋 Teacher List</h3>
                        <div class="flex items-center gap-2">
                            <input type="text" id="searchInput" placeholder="🔍 Search teachers..." 
                                   class="bg-gray-50 text-[#5C3A1E] rounded-lg px-4 py-2 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C3A1E] text-sm">
                            <span class="text-gray-500 text-sm">{{ $teachers->count() }} total</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="teachersTableBody" class="bg-white divide-y divide-gray-200">
                                @forelse($teachers as $index => $teacher)
                                    <tr class="hover:bg-gray-50 transition-all teacher-row">
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-[#5C3A1E]">{{ $teacher->username }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $teacher->name }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-mono">••••••••</span>
                                            <button onclick="openResetPassword({{ $teacher->id }}, '{{ $teacher->name }}')" class="ml-2 text-blue-500 hover:text-blue-700 text-xs" title="Reset password">
                                                🔑 Reset
                                            </button>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">Teacher</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $teacher->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <button onclick="openResetPassword({{ $teacher->id }}, '{{ $teacher->name }}')" class="text-blue-500 hover:text-blue-700 mr-2" title="Reset Password">
                                                🔑
                                            </button>
                                            @if($teacher->id !== Auth::id())
                                                <button onclick="deleteTeacher({{ $teacher->id }})" class="text-red-500 hover:text-red-700" title="Delete Teacher">
                                                    🗑️
                                                </button>
                                            @else
                                                <span class="text-gray-400 text-xs" title="Cannot delete your own account">🔒</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                            📭 No teachers found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .teacher-row {
            transition: all 0.2s ease;
        }
        .teacher-row:hover {
            background-color: #f3f0eb;
        }
        
        /* SweetAlert2 centering fix */
        .swal2-container {
            z-index: 9999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            width: 100vw !important;
        }
        
        .swal2-popup {
            margin: 0 auto !important;
            position: relative !important;
            top: auto !important;
            left: auto !important;
            transform: none !important;
            max-height: 90vh !important;
            overflow-y: auto !important;
        }
        
        body.swal2-shown {
            overflow: hidden !important;
        }
    </style>

    <script>
        // ============================================
        // TEACHER MANAGEMENT
        // ============================================

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.teacher-row');

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });

        // ============================================
        // REGISTER TEACHER
        // ============================================

        document.getElementById('teacherRegistrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
            
            const username = document.getElementById('regUsername').value.trim();
            const name = document.getElementById('regName').value.trim();
            const password = document.getElementById('regPassword').value;
            
            let hasError = false;
            
            // Validate
            if (!username || username.length < 3) {
                showError('regUsernameError', 'Username must be at least 3 characters');
                hasError = true;
            }
            
            if (!name) {
                showError('regNameError', 'Full name is required');
                hasError = true;
            }
            
            if (!password || password.length < 6) {
                showError('regPasswordError', 'Password must be at least 6 characters');
                hasError = true;
            }
            
            if (hasError) return;
            
            // Show loading
            Swal.fire({
                title: '⏳ Registering Teacher...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit
            fetch('{{ route("teacher.teachers.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    name: name,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '✅ Teacher Registered!',
                        html: `
                            <p>Teacher <strong>${data.teacher.name}</strong> has been created successfully.</p>
                            <div class="mt-4 p-3 bg-gray-100 rounded-lg text-left">
                                <p class="text-sm text-gray-600">Username: <strong>${data.teacher.username}</strong></p>
                            </div>
                            <p class="text-sm text-gray-500 mt-3">📋 Please share these credentials with the new teacher</p>
                        `,
                        icon: 'success',
                        confirmButtonColor: '#5C3A1E'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Check for validation errors
                    if (data.errors) {
                        const errors = data.errors;
                        if (errors.username) showError('regUsernameError', errors.username[0]);
                        if (errors.name) showError('regNameError', errors.name[0]);
                        if (errors.password) showError('regPasswordError', errors.password[0]);
                    } else {
                        Swal.fire({
                            title: '❌ Error',
                            text: data.error || 'Failed to register teacher',
                            icon: 'error',
                            confirmButtonColor: '#5C3A1E'
                        });
                    }
                }
            })
            .catch(error => {
                Swal.fire({
                    title: '❌ Error',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#5C3A1E'
                });
                console.error('Error:', error);
            });
        });

        function showError(elementId, message) {
            const el = document.getElementById(elementId);
            el.textContent = message;
            el.classList.remove('hidden');
        }

        // ============================================
        // RESET PASSWORD
        // ============================================

        function openResetPassword(teacherId, teacherName) {
            Swal.fire({
                title: `Reset Password for ${teacherName}`,
                html: `
                    <div class="text-left" style="text-align: left !important;">
                        <label class="text-sm text-gray-600 block mb-2" style="display: block; margin-bottom: 8px; font-size: 14px; color: #6b7280;">Enter new password (minimum 6 characters):</label>
                        <input type="password" id="newPassword" placeholder="Enter new password..." minlength="6" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px;">
                        <div class="mt-2 flex items-center gap-2" style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="showPassword" style="width: 16px; height: 16px; border-radius: 4px; border-color: #d1d5db;">
                            <label for="showPassword" class="text-sm text-gray-600" style="font-size: 14px; color: #6b7280;">Show password</label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#5C3A1E',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Reset Password',
                cancelButtonText: 'Cancel',
                position: 'center',
                preConfirm: () => {
                    const password = document.getElementById('newPassword').value;
                    if (!password || password.length < 6) {
                        Swal.showValidationMessage('⚠️ Password must be at least 6 characters');
                        return false;
                    }
                    return password;
                },
                didOpen: () => {
                    document.getElementById('showPassword').addEventListener('change', function() {
                        const passwordInput = document.getElementById('newPassword');
                        passwordInput.type = this.checked ? 'text' : 'password';
                    });
                    setTimeout(() => {
                        document.getElementById('newPassword').focus();
                    }, 100);
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    resetPassword(teacherId, result.value);
                }
            });
        }

        function resetPassword(teacherId, newPassword) {
            Swal.fire({
                title: '⏳ Resetting Password...',
                text: 'Please wait',
                allowOutsideClick: false,
                position: 'center',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/teacher/teachers/${teacherId}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ password: newPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: '✅ Password Reset!',
                        html: `
                            <p>Password has been reset successfully.</p>
                            <div class="mt-4 p-3 bg-gray-100 rounded-lg">
                                <p class="text-sm text-gray-600">New Password:</p>
                                <p class="text-xl font-bold text-[#5C3A1E] font-mono">${data.new_password}</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-3">📋 Please share this password with the teacher</p>
                        `,
                        icon: 'success',
                        confirmButtonColor: '#5C3A1E',
                        position: 'center'
                    }).then(() => {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(data.new_password).catch(() => {});
                        }
                    });
                } else {
                    Swal.fire({
                        title: '❌ Error',
                        text: data.error || 'Failed to reset password',
                        icon: 'error',
                        confirmButtonColor: '#5C3A1E',
                        position: 'center'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: '❌ Error',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#5C3A1E',
                    position: 'center'
                });
                console.error('Error:', error);
            });
        }

        // ============================================
        // DELETE TEACHER
        // ============================================

        function deleteTeacher(teacherId) {
            Swal.fire({
                title: 'Delete Teacher?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#5C3A1E',
                confirmButtonText: 'Yes, delete it!',
                position: 'center'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '⏳ Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        position: 'center',
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/teacher/teachers/${teacherId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Teacher has been deleted.',
                                icon: 'success',
                                confirmButtonColor: '#5C3A1E',
                                position: 'center'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.error || 'Failed to delete teacher',
                                icon: 'error',
                                confirmButtonColor: '#5C3A1E',
                                position: 'center'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong',
                            icon: 'error',
                            confirmButtonColor: '#5C3A1E',
                            position: 'center'
                        });
                        console.error('Error:', error);
                    });
                }
            });
        }

        // Make functions globally accessible
        window.openResetPassword = openResetPassword;
        window.resetPassword = resetPassword;
        window.deleteTeacher = deleteTeacher;
    </script>
@endsection