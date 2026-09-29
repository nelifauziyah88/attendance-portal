<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/invitation', 'users.invitations.index')
    ->name('users.invitations.index');

Route::view('/admin/login', 'admin.auth.login')
    ->name('admin.auth.login');

Route::view('/admin/dashboard', 'admin.dashboard')
    ->name('admin.dashboard');

Route::view('/admin/employee/information', 'admin.employee.index')
    ->name('admin.employee.index');

Route::view('/admin/confirmation/attendance', 'admin.confirmation.index')
    ->name('admin.confirmation.index');

Route::view('/admin/attendance/list', 'admin.attendance.index')
    ->name('admin.attendance.index');

Route::view('/admin/lucky-spin', 'admin.lucky_spin.lucky_spin')
    ->name('admin.lucky-spin');

Route::view('/admin/lucky-spin/display', 'admin.lucky_spin.lucky_spin_display')
    ->name('admin.lucky-spin.display');

Route::view('/check-in', 'users.attendance.index')
    ->name('attendance.index');