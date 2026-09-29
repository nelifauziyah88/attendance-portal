<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/invitation', 'users.invitations.index')
    ->name('users.invitations.index');
