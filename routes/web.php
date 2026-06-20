<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login',  [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill(['password' => \Illuminate\Support\Facades\Hash::make($password)]);
            $user->save();
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login.show')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

// ─── Protected Routes (require login) ────────────────────────────────────────

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/leads/create',            [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads',                  [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads',                   [LeadController::class, 'adminIndex'])->name('leads.index');
    Route::get('/leads/export',            [LeadController::class, 'exportCsv'])->name('leads.export');
    Route::get('/leads/import',            [LeadController::class, 'showImport'])->name('leads.import');
    Route::get('/leads/import/template',   [LeadController::class, 'downloadTemplate'])->name('leads.import.template');
    Route::post('/leads/import/preview',   [LeadController::class, 'importPreview'])->name('leads.import.preview');
    Route::post('/leads/import/confirm',   [LeadController::class, 'importConfirm'])->name('leads.import.confirm');
    Route::delete('/leads/{lead}',         [LeadController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/{lead}/assign',    [LeadController::class, 'assign'])->name('leads.assign');
    Route::get('/leads/{lead}/activity',   [LeadController::class, 'activity'])->name('leads.activity');
    Route::post('/leads/{lead}/summarize', [LeadController::class, 'summarizeNotes'])->name('leads.summarize');
    Route::get('/leaderboard',             [DashboardController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/leads/my-leads',          [LeadController::class, 'staffIndex'])->name('leads.myleads');
    Route::get('/leads/update',            [LeadController::class, 'updateIndex'])->name('leads.update.index');
    Route::post('/leads/{lead}/status',    [LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::post('/leads/{lead}/notes',     [LeadController::class, 'addNote'])->name('leads.notes.add');
    Route::post('/leads/check-duplicate',  [LeadController::class, 'checkDuplicate'])->name('leads.check-duplicate');

    Route::get('/settings',          [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile',  [SettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [SettingsController::class, 'changePassword'])->name('settings.change-password');

    // Admin-only: User Management
    Route::get('/users',                      [SettingsController::class, 'manageUsers'])->name('users.index');
    Route::post('/users',                     [SettingsController::class, 'createStaff'])->name('users.store');
    Route::put('/users/{user}/toggle-active', [SettingsController::class, 'toggleActive'])->name('users.toggle-active');
});