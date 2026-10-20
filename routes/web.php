<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameSaveController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Api\CycleResultController;
use App\Http\Controllers\TeacherQuestionController;
use App\Http\Controllers\StudentAssignmentController;


Route::get('/cycle1/results', [CycleResultController::class, 'getCycle1Results']);
Route::get('/cycle2/results', [CycleResultController::class, 'getCycle2Results']);

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');

// ============================================
// PROTECTED ROUTES (Require Authentication)
// ============================================
Route::middleware('auth')->group(function () {

    Route::post('/create-teacher', [AuthController::class, 'createTeacher']);

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/teacher', function () {
        return view('teacher.dashboard');
    })->name('teacher');

    // Lessons
    Route::get('/lessons', function () {
        return view('lessons.index');
    })->name('lessons.index');

    // Lesson 1-11 specific routes
    Route::get('/lessons/lesson1', function () {
        return view('lessons.lesson1');
    });
    Route::get('/lessons/lesson2', function () {
        return view('lessons.lesson2');
    });
    Route::get('/lessons/lesson3', function () {
        return view('lessons.lesson3');
    });
    Route::get('/lessons/lesson4', function () {
        return view('lessons.lesson4');
    });
    Route::get('/lessons/lesson5', function () {
        return view('lessons.lesson5');
    });
    Route::get('/lessons/lesson6', function () {
        return view('lessons.lesson6');
    });
    Route::get('/lessons/lesson7', function () {
        return view('lessons.lesson7');
    });
    Route::get('/lessons/lesson8', function () {
        return view('lessons.lesson8');
    });
    Route::get('/lessons/lesson9', function () {
        return view('lessons.lesson9');
    });
    Route::get('/lessons/lesson10', function () {
        return view('lessons.lesson10');
    });
    Route::get('/lessons/lesson11', function () {
        return view('lessons.lesson11');
    });

    // Game Routes
    Route::prefix('game')->group(function () {
        Route::get('/levels', function () {
            return view('game.levels');
        })->name('game.levels');

        Route::get('/level-details', function () {
            return view('game.level-details');
        })->name('game.level-details');

        // Regular cycle 1 (for levels 1-15 and 21-25)
        Route::get('/cycle1', function () {
            $level = request('level', 1);
            // If level is between 16-20, use drag-drop version
            if ($level >= 16 && $level <= 20) {
                return view('game.cycle1-dragdrop');
            }
            return view('game.cycle1');
        })->name('game.cycle1');

        Route::get('/cycle2', function () {
            return view('game.cycle2');
        })->name('game.cycle2');
    });
    Route::get('/game', function () {
        return redirect('/game/levels');
    });

    // Performance
    Route::get('/performance', function () {
        return view('dashboard.performance');
    })->name('performance');
});

// Home redirect
Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function () {
    // Save routes
    Route::post('/save-cycle1', [GameSaveController::class, 'saveCycle1']);
    Route::post('/save-cycle2', [GameSaveController::class, 'saveCycle2']);

    // Fetch results routes
    Route::get('/api/cycle1/results', [GameSaveController::class, 'getCycle1Results']);
    Route::get('/api/cycle2/results', [GameSaveController::class, 'getCycle2Results']);
});

// Question Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/teacher/questions', [TeacherQuestionController::class, 'index'])->name('teacher.questions.index');
    Route::get('/teacher/questions/create', [TeacherQuestionController::class, 'create'])->name('teacher.questions.create');
    Route::post('/teacher/questions/preview', [TeacherQuestionController::class, 'preview'])->name('teacher.questions.preview');
    Route::post('/teacher/questions', [TeacherQuestionController::class, 'store'])->name('teacher.questions.store');
    Route::get('/teacher/questions/{id}', [TeacherQuestionController::class, 'show'])->name('teacher.questions.show');
    Route::get('/teacher/questions/{id}/edit', [TeacherQuestionController::class, 'edit'])->name('teacher.questions.edit');
    Route::put('/teacher/questions/{id}', [TeacherQuestionController::class, 'update'])->name('teacher.questions.update');
    Route::delete('/teacher/questions/{id}', [TeacherQuestionController::class, 'destroy'])->name('teacher.questions.destroy');
    Route::post('/teacher/questions/{id}/toggle-public', [TeacherQuestionController::class, 'togglePublic'])->name('teacher.questions.toggle-public');
    Route::get('/teacher/questions/{id}/students', [TeacherQuestionController::class, 'students'])->name('teacher.questions.students');
    Route::get(
        '/teacher/questions/{assignmentId}/students/{studentId}/results',
        [TeacherQuestionController::class, 'viewStudentResults']
    )->name('teacher.questions.students.results');
});

// Student Assignment Routes
Route::middleware('auth')->group(function () {
    Route::get('/student/assignments', [StudentAssignmentController::class, 'index'])->name('student.assignments.index');
    Route::get('/student/assignments/{id}', [StudentAssignmentController::class, 'show'])->name('student.assignments.show');
    Route::post('/student/assignments/submit', [StudentAssignmentController::class, 'submitAnswer'])->name('student.assignments.submit');
    Route::post('/student/assignments/{id}/complete', [StudentAssignmentController::class, 'complete'])->name('student.assignments.complete');
    Route::get('/student/assignments/{id}/results', [StudentAssignmentController::class, 'results'])->name('student.assignments.results');
    Route::post('/student/assignments/enroll', [StudentAssignmentController::class, 'enroll'])->name('student.assignments.enroll');
    Route::get('/student/assignments/{id}/attempts', [StudentAssignmentController::class, 'getAttempts'])->name('student.assignments.attempts');
    Route::get('/student/assignments/{id}/timer', [StudentAssignmentController::class, 'getTimerStatus'])->name('student.assignments.timer');
    Route::get('/student/assignments/{id}/progress', [StudentAssignmentController::class, 'getProgress'])->name('student.assignments.progress');
    Route::post('/student/assignments/{id}/save-progress', [StudentAssignmentController::class, 'saveProgress'])->name('student.assignments.save-progress');
});

/////////////////API ROUTES///////////////////////////////

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LessonProgressController;

// Get current user info
Route::get('/api/user', function () {
    if (Auth::check()) {
        return response()->json([
            'id' => Auth::id(),
            'username' => Auth::user()->username,
        ]);
    }
    return response()->json(null);
});

// Get user stats
Route::get('/api/user/stats', [App\Http\Controllers\GameProgressController::class, 'getUserProgress']);


Route::middleware('auth')->group(function () {
    // Save routes
    Route::post('/save-cycle1', [GameSaveController::class, 'saveCycle1']);
    Route::post('/save-cycle2', [GameSaveController::class, 'saveCycle2']);

    // Fetch results routes
    Route::get('/api/cycle1/results', [GameSaveController::class, 'getCycle1Results']);
    Route::get('/api/cycle2/results', [GameSaveController::class, 'getCycle2Results']);
    Route::get('/api/user/stats', [GameSaveController::class, 'getUserStats']);
    Route::get('/api/game/levels/all', [GameSaveController::class, 'getAllLevelsProgress']);

    Route::get('/api/teacher/cycle2/results', [GameSaveController::class, 'getTeacherCycle2Results']);
    Route::get('/api/teacher/cycle1/results', [GameSaveController::class, 'getTeacherCycle1Results']);
});

Route::get('/api/students/count', function () {
    $count = \App\Models\User::where('role', 'student')->count();
    return response()->json(['count' => $count]);
})->middleware('auth');


Route::middleware('auth')->group(function () {
    // Lesson progress routes
    Route::get('/api/lesson-progress', [LessonProgressController::class, 'getProgress']);
    Route::post('/api/lesson-complete', [LessonProgressController::class, 'completeLesson']);
    Route::get('/api/total-levels', [GameSaveController::class, 'getTotalLevels']);
    Route::get('/api/total-lessons', [GameSaveController::class, 'getTotalLessons']);
});


Route::get('/api/students/stats', function () {
    $total = \App\Models\User::where('role', 'student')->count();

    $currentYear = now()->year;
    $thisYear = \App\Models\User::where('role', 'student')
        ->whereYear('created_at', $currentYear)
        ->count();


    return response()->json([
        'total' => $total,
        'thisYear' => $thisYear
    ]);
})->middleware('auth');



// Student Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/teacher/students', [TeacherController::class, 'index'])->name('teacher.students');
    Route::post('/teacher/students/{id}/reset-password', [TeacherController::class, 'resetPassword'])->name('students.reset-password');
    Route::delete('/teacher/students/{id}', [TeacherController::class, 'destroy'])->name('students.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/teacher/students/{id}/performance', [TeacherController::class, 'performance'])->name('teacher.student.performance');

    // API routes for student performance data
    Route::get('/api/user/{id}', [TeacherController::class, 'getUser']);
    Route::get('/api/cycle1/results/{userId}', [TeacherController::class, 'getCycle1Results']);
    Route::get('/api/cycle2/results/{userId}', [TeacherController::class, 'getCycle2Results']);
});

Route::middleware('auth')->group(function () {
    Route::get('/teacher/teachers', [TeacherController::class, 'indexTeacher'])->name('teacher.teachers');
    Route::post('/teacher/teachers', [TeacherController::class, 'store'])->name('teacher.teachers.store');
    Route::delete('/teacher/teachers/{id}', [TeacherController::class, 'destroyTeacher'])->name('teacher.teachers.destroy');
    Route::post('/teacher/teachers/{id}/reset-password', [TeacherController::class, 'resetPasswordTeacher'])->name('teacher.teachers.reset-password');
});
