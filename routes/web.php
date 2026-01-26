<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\EnrollmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes (Localized)
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'create'])->name('login');
    Route::post('/masuk', [AuthController::class, 'store'])->middleware('throttle:5,1');
    
    // Register Routes
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::post('/keluar', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    
    // Dashboard (Common Redirector)
    Route::get('/dasbor', [DashboardController::class, 'index'])->name('dashboard');

    // Settings
    Route::get('/pengaturan', [\App\Http\Controllers\SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/pengaturan', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');

    // Certificate
    Route::get('/modul/{module}/sertifikat', [\App\Http\Controllers\CertificateController::class, 'show'])->name('modules.certificate');

    // ----------------------------------------------------------------------
    // PLACEMENT QUIZ (for students who haven't completed)
    // ----------------------------------------------------------------------
    Route::middleware(['role:student'])->prefix('penempatan')->name('placement.')->group(function () {
        Route::get('/', [PlacementController::class, 'index'])->name('index');
        Route::get('/mulai', [PlacementController::class, 'start'])->name('start');
        Route::post('/kirim', [PlacementController::class, 'submit'])->name('submit');
        Route::get('/hasil', [PlacementController::class, 'result'])->name('result');
    });

    // ----------------------------------------------------------------------
    // STUDENT ROUTES (require placement completed)
    // ----------------------------------------------------------------------
    Route::middleware(['role:student', 'placement.completed'])->prefix('siswa')->name('student.')->group(function () {
        // Modules (browsing)
        Route::get('/modul', [ModuleController::class, 'index'])->name('modules.index');
        Route::get('/modul/{module}', [ModuleController::class, 'show'])->name('modules.show');
        
        // Enrollment
        Route::post('/modul/{module}/daftar', [EnrollmentController::class, 'enroll'])->name('modules.enroll');
        Route::delete('/modul/{module}/keluar', [EnrollmentController::class, 'unenroll'])->name('modules.unenroll');
        
        // Lessons (require enrollment)
        Route::middleware(['enrolled'])->group(function () {
            Route::get('/modul/{module}/pelajaran/{lesson}', [\App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
            
            // Quizzes
            Route::get('/modul/{module}/pelajaran/{lesson}/kuis', [\App\Http\Controllers\QuizController::class, 'start'])->name('quizzes.start');
            Route::post('/modul/{module}/pelajaran/{lesson}/kuis', [\App\Http\Controllers\QuizController::class, 'submit'])->name('quizzes.submit')->middleware('throttle:10,1');
        });
        
        // Project Submissions (require enrollment)
        Route::middleware(['enrolled'])->group(function () {
            Route::get('/modul/{module}/proyek', [\App\Http\Controllers\ProjectSubmissionController::class, 'create'])->name('projects.create');
            Route::post('/modul/{module}/proyek', [\App\Http\Controllers\ProjectSubmissionController::class, 'store'])->name('projects.store');
            Route::get('/modul/{module}/proyek/{submission}', [\App\Http\Controllers\ProjectSubmissionController::class, 'show'])->name('projects.show');
        });
    });

    // ----------------------------------------------------------------------
    // LECTURER ROUTES
    // ----------------------------------------------------------------------
    Route::middleware(['role:lecturer'])->prefix('dosen')->name('lecturer.')->group(function () {
        // Module Management
        Route::get('/modul', [ModuleController::class, 'index'])->name('modules.index');
        Route::get('/modul/buat', [ModuleController::class, 'create'])->name('modules.create');
        Route::post('/modul', [ModuleController::class, 'store'])->name('modules.store');
        Route::get('/modul/{module}', [ModuleController::class, 'show'])->name('modules.show');
        Route::get('/modul/{module}/peserta', [ModuleController::class, 'learners'])->name('modules.learners');
        Route::delete('/modul/{module}/peserta/{user}', [ModuleController::class, 'kick'])->name('modules.kick');
        
        Route::get('/modul/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
        Route::put('/modul/{module}', [ModuleController::class, 'update'])->name('modules.update');
        Route::delete('/modul/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

        // Lesson Management (Nested)
        Route::get('/modul/{module}/pelajaran/buat', [\App\Http\Controllers\LessonController::class, 'create'])->name('lessons.create');
        Route::post('/modul/{module}/pelajaran', [\App\Http\Controllers\LessonController::class, 'store'])->name('lessons.store');
        Route::get('/modul/{module}/pelajaran/{lesson}', [\App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
        Route::get('/modul/{module}/pelajaran/{lesson}', [\App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
        Route::get('/modul/{module}/pelajaran/{lesson}/edit', [\App\Http\Controllers\LessonController::class, 'edit'])->name('lessons.edit');

        // Student Management
        Route::get('/siswa', [\App\Http\Controllers\LecturerStudentController::class, 'index'])->name('students.index');
        Route::put('/modul/{module}/pelajaran/{lesson}', [\App\Http\Controllers\LessonController::class, 'update'])->name('lessons.update');
        Route::delete('/modul/{module}/pelajaran/{lesson}', [\App\Http\Controllers\LessonController::class, 'destroy'])->name('lessons.destroy');

        // Quiz Management (Strictly Nested under Module)
        Route::get('/modul/{module}/quiz/buat', [\App\Http\Controllers\QuizController::class, 'create'])->name('quizzes.create');
        Route::post('/modul/{module}/quiz', [\App\Http\Controllers\QuizController::class, 'store'])->name('quizzes.store');
        Route::get('/modul/{module}/quiz/{quiz}/edit', [\App\Http\Controllers\QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/modul/{module}/quiz/{quiz}', [\App\Http\Controllers\QuizController::class, 'update'])->name('quizzes.update');
        Route::delete('/modul/{module}/quiz/{quiz}', [\App\Http\Controllers\QuizController::class, 'destroy'])->name('quizzes.destroy');
        
        // Project Submission Management
        Route::get('/modul/{module}/submissions', [\App\Http\Controllers\ProjectSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/modul/{module}/submissions/{submission}', [\App\Http\Controllers\ProjectSubmissionController::class, 'review'])->name('submissions.review');
        Route::put('/modul/{module}/submissions/{submission}', [\App\Http\Controllers\ProjectSubmissionController::class, 'grade'])->name('submissions.grade');

        // Bank Soal (Question Bank for Placement Quiz)
        Route::resource('bank-soal', \App\Http\Controllers\Lecturer\BankSoalController::class)->parameters(['bank-soal' => 'bankSoal']);
        Route::patch('/bank-soal/{bankSoal}/toggle-active', [\App\Http\Controllers\Lecturer\BankSoalController::class, 'toggleActive'])->name('bank-soal.toggle-active');
    });

    // ----------------------------------------------------------------------
    // ADMIN ROUTES
    // ----------------------------------------------------------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
         Route::get('/users', function() { return 'User Management'; })->name('users.index');
    });

});

Route::get('/', function () {
    return view('landing');
})->name('home');

