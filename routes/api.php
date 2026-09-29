<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventInvitationController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/participants', [ParticipantController::class, 'index']);
    Route::post('/participants', [ParticipantController::class, 'store']);

    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/{eventId}', [EventController::class, 'show'])->whereNumber('eventId');
    Route::put('/events/{eventId}', [EventController::class, 'update'])->whereNumber('eventId');
    Route::delete('/events/{eventId}', [EventController::class, 'destroy'])->whereNumber('eventId');

    Route::get('/events/{eventId}/quota', [EventInvitationController::class, 'quota'])->whereNumber('eventId');
    Route::get('/events/{eventId}/invitations', [EventInvitationController::class, 'index'])->whereNumber('eventId');
    Route::post('/events/{eventId}/invitations', [EventInvitationController::class, 'store'])->whereNumber('eventId');
    Route::post('/events/{eventId}/invitations/send-emails', [EventInvitationController::class, 'sendEmails'])->whereNumber('eventId');
});

Route::get('/invitations/{code}', [InvitationController::class, 'show']);
Route::get('/invitations/{code}/qr', [InvitationController::class, 'qr']);
Route::post('/invitations/{code}/confirm', [InvitationController::class, 'confirm']);
