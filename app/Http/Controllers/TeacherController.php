<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use App\Models\Cycle1Result;
use App\Models\Cycle2Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
      public function dashboard()
    {
        $levels = Level::orderBy('level_number')->get();
        return view('teacher.dashboard', compact('levels'));
    }
    // Display all students
    public function index()
    {
        // Get all users with role 'student'
        $students = User::where('role', 'student')->orderBy('name')->get();
        return view('teacher.students', compact('students'));
    }

    // Reset student password with custom password
    public function resetPassword(Request $request, $id)
    {
        $student = User::findOrFail($id);
        
        // Check if user is a teacher
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the new password
        $request->validate([
            'password' => 'required|min:6|string'
        ]);

        // Update password
        $student->password = Hash::make($request->password);
        $student->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'new_password' => $request->password
        ]);
    }

    // Delete student
    public function destroy($id)
    {
        $student = User::findOrFail($id);
        
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }

      // Show student performance
    public function performance($id)
    {
        $student = User::findOrFail($id);
        
        // Check if the user is a teacher
        if (Auth::user()->role !== 'teacher') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('teacher.student-performance', ['studentId' => $id]);
    }

    // Get user by ID (API)
    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
        ]);
    }

    // Get Cycle 1 results for a specific user (API)
    public function getCycle1Results($userId)
    {
        $results = Cycle1Result::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($results);
    }

    // Get Cycle 2 results for a specific user (API)
    public function getCycle2Results($userId)
    {
        $results = Cycle2Result::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($results);
    }

    // Display all teachers and registration form
    public function indexTeacher()
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();
        return view('teacher.teachers', compact('teachers'));
    }

    // Store a new teacher
    public function store(Request $request)
    {
        // Check if user is a teacher
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users_congak,username',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $teacher = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully',
            'teacher' => $teacher
        ]);
    }

    // Delete a teacher
    public function destroyTeacher($id)
    {
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $teacher = User::where('role', 'teacher')->findOrFail($id);
        
        // Prevent deleting yourself
        if ($teacher->id === Auth::id()) {
            return response()->json([
                'error' => 'You cannot delete your own account'
            ], 403);
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully'
        ]);
    }

    // Reset teacher password
    public function resetPasswordTeacher(Request $request, $id)
    {
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $teacher->password = Hash::make($request->password);
        $teacher->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully',
            'new_password' => $request->password
        ]);
    }
}
