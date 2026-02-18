<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/track', function () {
    return view('track.index');
})->name('track');

Route::get('/submit', function () {
    return view('submit.index');
})->name('submit');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Activation routes
Route::get('/activate/{token}', [AuthController::class, 'showActivationForm'])->name('activation.form');
Route::post('/api/activate', [AuthController::class, 'activate'])->name('activation.activate');
Route::post('/api/resend-activation', [AuthController::class, 'resendActivation'])
    ->middleware('throttle:3,60') // 3 per hour
    ->name('activation.resend');

// API Routes
Route::post('/api/submit-document', [DocumentController::class, 'submit']);
Route::post('/api/track-document', [DocumentController::class, 'track']);
Route::post('/api/check-email', [AuthController::class, 'checkEmail']);
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1'); // 5 per minute
Route::post('/api/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route
Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/api/profile', [ProfileController::class, 'update']);
    Route::put('/api/profile/password', [ProfileController::class, 'changePassword']);

    // Admin routes (admin-only)
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::put('/api/admin/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/api/admin/users/{id}', [AdminController::class, 'deleteUser']);

        Route::get('/admin/documents', [AdminController::class, 'documents'])->name('admin.documents');
        Route::put('/api/admin/documents/{id}', [AdminController::class, 'updateDocument']);
        Route::delete('/api/admin/documents/{id}', [AdminController::class, 'deleteDocument']);
    });
});


