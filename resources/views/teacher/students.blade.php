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
                    <a href="{{ route('teacher.students') }}" class="flex items-center space-x-3 bg-[#5C3A1E] text-white px-4 py-3 rounded-xl transition-all shadow-md">
                        <span class="text-2xl">👨‍🎓</span>
                        <span class="font-semibold">Students</span>
                    </a>
                    <a href="{{ route('teacher.teachers') }}"  class="flex items-center space-x-3 text-gray-600 hover:bg-[#5C3A1E]/10 px-4 py-3 rounded-xl transition-all">
                        <span class="text-2xl">👨‍🏫</span>
                        <span class="font-semibold">Teachers</span>
                    </a>
                </nav>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-[#5C3A1E]/20 flex items-center justify-center text-2xl">
                            📕
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
                <a href="#" class="text-[#5C3A1E] text-2xl">👨‍🎓</a>
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
                                <span>👨‍🎓</span>
                                <span>Student Management</span>
                            </h1>
                            <p class="text-white/70 text-sm mt-1">View and manage all student accounts</p>
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

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Total Students</p>
                                <p class="text-3xl font-bold text-[#5C3A1E] mt-1">{{ $students->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-3xl">👨‍🎓</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-shadow duration-300" hidden>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">Active Students</p>
                                <p class="text-3xl font-bold text-[#F4A623] mt-1">{{ $students->where('created_at', '>=', now()->subDays(30))->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-3xl">✅</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs uppercase tracking-wider">New This Year</p>
                                <p class="text-3xl font-bold text-[#5C3A1E] mt-1">{{ $students->where('created_at', '>=', now()->startOfYear())->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-3xl">📅</div>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="bg-white rounded-2xl p-6 shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[#5C3A1E] font-bold text-lg">📋 Student List</h3>
                        <div class="flex items-center gap-2">
                            <input type="text" id="searchInput" placeholder="🔍 Search students..." class="bg-gray-50 text-[#5C3A1E] rounded-lg px-4 py-2 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-[#5C3A1E] text-sm">
                            <span class="text-gray-500 text-sm">{{ $students->count() }} total</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                                @forelse($students as $index => $student)
                                    <tr class="hover:bg-gray-50 transition-all student-row cursor-pointer" onclick="viewStudentPerformance({{ $student->id }})">
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-[#5C3A1E]">{{ $student->username }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $student->name }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Student</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $student->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <button onclick="event.stopPropagation(); openResetPassword({{ $student->id }}, '{{ $student->name }}')" class="text-blue-500 hover:text-blue-700 mr-2" title="Reset Password">
                                                🔑
                                            </button>
                                            <button onclick="event.stopPropagation(); deleteStudent({{ $student->id }})" class="text-red-500 hover:text-red-700" title="Delete Student">
                                                🗑️
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                                            📭 No students found
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
        .student-row {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .student-row:hover {
            background-color: #f3f0eb;
            transform: scale(1.01);
        }
        .student-row td:first-child {
            border-left: 3px solid transparent;
        }
        .student-row:hover td:first-child {
            border-left-color: #5C3A1E;
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
        // STUDENT MANAGEMENT - TEACHER VIEW
        // ============================================

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const rows = document.querySelectorAll('.student-row');

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

        // View student performance - Navigate to performance page
        function viewStudentPerformance(studentId) {
            window.location.href = `/teacher/students/${studentId}/performance`;
        }

        // Open reset password dialog
        function openResetPassword(studentId, studentName) {
            event.stopPropagation();
            
            Swal.fire({
                title: `Reset Password for ${studentName}`,
                html: `
                    <div class="text-left" style="text-align: left !important;">
                        <label class="text-sm text-gray-600 block mb-2" style="display: block; margin-bottom: 8px; font-size: 14px; color: #6b7280;">Enter new password (minimum 6 characters):</label>
                        <input type="password" id="newPassword" placeholder="Enter new password..." minlength="6" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px;">
                        <div class="mt-2 flex items-center gap-2" style="margin-top: 8px; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="showPassword" style="width: 16px; height: 16px; border-radius: 4px; border-color: #d1d5db;">
                            <label for="showPassword" class="text-sm text-gray-600" style="font-size: 14px; color: #6b7280;">Show password</label>
                        </div>
                        <div class="mt-2 text-sm text-gray-500" style="margin-top: 8px; font-size: 14px; color: #9ca3af;">
                            <p>💡 Tip: Can use a combination of letters, numbers, and symbols</p>
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
                    resetPassword(studentId, result.value);
                }
            });
        }

        // Reset password function
        function resetPassword(studentId, newPassword) {
            Swal.fire({
                title: '⏳ Resetting Password...',
                text: 'Please wait',
                allowOutsideClick: false,
                position: 'center',
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/teacher/students/${studentId}/reset-password`, {
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
                            <p class="text-sm text-gray-500 mt-3">📋 Please share this password with the student</p>
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

        // Delete student function
        function deleteStudent(studentId) {
            event.stopPropagation();
            
            Swal.fire({
                title: 'Delete Student?',
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

                    fetch(`/teacher/students/${studentId}`, {
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
                                text: 'Student has been deleted.',
                                icon: 'success',
                                confirmButtonColor: '#5C3A1E',
                                position: 'center'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.error || 'Failed to delete student',
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
        window.viewStudentPerformance = viewStudentPerformance;
        window.openResetPassword = openResetPassword;
        window.resetPassword = resetPassword;
        window.deleteStudent = deleteStudent;
    </script>
@endsection