<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login request
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|in:student,teacher', // Add role validation
        ]);

        // Find user by username
        $user = User::where('username', $request->username)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid username or password!']);
        }
        
        // Check if selected role matches user's actual role
        $selectedRole = $request->role;
        if ($user->role !== $selectedRole) {
            return response()->json([
                'success' => false, 
                'message' => 'You are not registered as a ' . $selectedRole . '! Please select the correct role.'
            ]);
        }
        
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Store user role in session
            session(['user_role' => $user->role]);
            
            // Redirect based on role
            if ($user->role === 'teacher') {
                return response()->json([
                    'success' => true, 
                    'redirect' => '/teacher', 
                    'role' => 'teacher'
                ]);
            } else {
                return response()->json([
                    'success' => true, 
                    'redirect' => '/dashboard', 
                    'role' => 'student'
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid username or password!']);
    }

    // Show signup page
    public function showSignup()
    {
        return view('auth.signup');
    }

    // Handle signup request
    public function signup(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4|confirmed',
        ]);

        // Create new user with role 'student' by default
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => null,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Auth::login($user);
        session(['user_role' => 'student']);

        return response()->json(['success' => true, 'redirect' => '/dashboard']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        session()->forget('user_role');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}