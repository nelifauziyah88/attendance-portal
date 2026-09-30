<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('invitation.index');
});

// Invitation Routes
Route::get('/invitation', [InvitationController::class, 'index'])->name('invitation.index');
Route::get('/api/employee/{badgeId}', [InvitationController::class, 'findEmployee'])->name('api.employee.find');
Route::post('/invitation', [InvitationController::class, 'store'])->name('invitation.store');

// Check-in Routes
Route::get('/check-in', [CheckInController::class, 'index'])->name('checkin.index');
Route::get('/api/check-in/employee/{badgeId}', [CheckInController::class, 'findEmployee'])->name('api.checkin.find');
Route::post('/check-in', [CheckInController::class, 'store'])->name('checkin.store');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    
    // 1. Ringkasan Dashboard & Statistik Utama
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // 2. Data Master Karyawan (pgsql_portal)
    Route::get('/employee/information', [AdminController::class, 'employee'])->name('employee.index');
    // 3. Rekap Konfirmasi RSVP (pgsql)
    Route::get('/confirmation/attendance', [AdminController::class, 'confirmation'])->name('confirmation.index');
    // 4. Rekap Check-In Hari-H (pgsql)
    Route::get('/attendance/list', [AdminController::class, 'attendance'])->name('attendance.index');

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

});


// Route::view('/admin/login', 'admin.auth.login')
//     ->name('admin.auth.login');

// Route::view('/admin/dashboard', 'admin.dashboard')
//     ->name('admin.dashboard');

Route::view('/admin/lucky-spin', 'admin.lucky_spin.lucky_spin')
    ->name('admin.lucky-spin');

Route::view('/admin/lucky-spin/display', 'admin.lucky_spin.lucky_spin_display')
    ->name('admin.lucky-spin.display');

// Route::view('/check-in', 'users.attendance.index')
//     ->name('attendance.index');