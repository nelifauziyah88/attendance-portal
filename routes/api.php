<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventInvitationController as AdminEventInvitationController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\EventInvitationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/participants', [ParticipantController::class, 'index']);
    Route::post('/participants', [ParticipantController::class, 'store']);

    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/{eventId}', [EventController::class, 'show'])->whereNumber('eventId');
    Route::put('/events/{eventId}', [EventController::class, 'update'])->whereNumber('eventId');
    Route::delete('/events/{eventId}', [EventController::class, 'destroy'])->whereNumber('eventId');
    Route::get('/events/{eventId}/qr', [EventController::class, 'qr'])->whereNumber('eventId');

    Route::get('/events/{eventId}/quota', [AdminEventInvitationController::class, 'quota'])->whereNumber('eventId');
    Route::get('/events/{eventId}/invitations', [AdminEventInvitationController::class, 'index'])->whereNumber('eventId');
    Route::post('/events/{eventId}/invitations', [AdminEventInvitationController::class, 'store'])->whereNumber('eventId');
});

Route::get('/events/{slug}', [EventInvitationController::class, 'show']);
Route::get('/events/{slug}/form-options', [EventInvitationController::class, 'formOptions']);
Route::post('/events/{slug}/check', [EventInvitationController::class, 'check']);
Route::post('/events/{slug}/confirm', [EventInvitationController::class, 'confirm']);
