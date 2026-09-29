<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;

Route::get('/participants', [ParticipantController::class, 'index']);
Route::post('/participants', [ParticipantController::class, 'store']);

Route::get('/invitations', [InvitationController::class, 'index']);
Route::post('/invitations', [InvitationController::class, 'store']);
Route::get('/invitations/quota', [InvitationController::class, 'quota']);
Route::get('/invitations/{badgeId}', [InvitationController::class, 'show']);
Route::get('/invitations/{badgeId}/qr', [InvitationController::class, 'qr']);
Route::post('/invitations/{badgeId}/confirm', [InvitationController::class, 'confirm']);
