<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

// Login Routes
Route::get('/', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $status = Password::sendResetLink(
        $request->only('email')
    );
    
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ]);
            
            $user->save();
        }
    );
    
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// Protected Routes (require login)
Route::middleware(['auth'])->group(function () {
    // Dashboard - Different for admin/staff
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin: Add new lead
Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');


    // Leads routes - controllers will check roles
    Route::get('/leads', [LeadController::class, 'adminIndex'])->name('leads.index');
    Route::get('/leads/export', [LeadController::class, 'exportCsv'])->name('leads.export');
    Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');
    Route::get('/leads/{lead}/activity', [LeadController::class, 'activity'])->name('leads.activity');
    
    // Staff routes - controllers will check roles
    Route::get('/leads/my-leads', [LeadController::class, 'staffIndex'])->name('leads.myleads');
    Route::get('/leads/update', [LeadController::class, 'updateIndex'])->name('leads.update.index');
    Route::post('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::post('/leads/{lead}/notes', [LeadController::class, 'addNote'])->name('leads.notes.add');
    
    // Settings Routes (both admin and staff)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.change-password');
    // Admin: User Management
Route::get('/users', [SettingsController::class, 'manageUsers'])->name('users.index');
Route::put('/users/{user}/toggle-active', [SettingsController::class, 'toggleActive'])->name('users.toggle-active');
});