<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/invitation', 'users.invitations.index')
    ->name('users.invitations.index');

Route::view('/invitation/qr', 'users.invitations.qr_scan')
    ->name('invitation.qr');

Route::view('/admin/login', 'admin.login')
    ->name('admin.login');
