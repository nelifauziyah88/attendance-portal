<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LuckySpinController;
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
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // 1. Ringkasan Dashboard & Statistik Utama
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // 2. Atur Schedule Check-In (Forward to DB for Set Schedule)    
    Route::post('/schedule', [AdminController::class, 'updateSchedule'])->name('schedule.update');

    // 3. Data Master Karyawan (pgsql_portal)
    Route::get('/employee/information', [AdminController::class, 'employee'])->name('employee.index');
    Route::post('/employee/{badge}/manager', [AdminController::class, 'updateManager'])->name('employee.manager');
    Route::patch('/employee/{badge}/manager', [AdminController::class, 'updateManager'])->name('employee.manager.update');

    // 4. Rekap Konfirmasi RSVP (pgsql)
    Route::get('/confirmation/attendance', [AdminController::class, 'confirmation'])->name('confirmation.index');

    // 5. Rekap Check-In Hari-H (pgsql)
    Route::get('/attendance/list', [AdminController::class, 'attendance'])->name('attendance.index');

    // 6. Rekap Hadiah
    Route::get('/prizes', [AdminController::class, 'prizes'])->name('prizes.index');

    Route::prefix('lucky-spin')->name('lucky-spin.')->group(function () {
        Route::get('/', [LuckySpinController::class, 'index'])->name('index');
        Route::post('/draw', [LuckySpinController::class, 'draw'])->name('draw');
        Route::delete('/forfeit/{badge}', [LuckySpinController::class, 'forfeit'])->name('forfeit');
        Route::get('/display', [LuckySpinController::class, 'display'])->name('display');
    });
});