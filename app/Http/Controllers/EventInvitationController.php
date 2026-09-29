<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckInvitationRequest;
use App\Http\Requests\ConfirmInvitationRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\InvitationResource;
use App\Http\Responses\ApiResponse;
use App\Services\EventService;
use App\Services\InvitationService;
use App\Services\ParticipantService;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;

class EventInvitationController extends Controller
{
    public function __construct(
        private readonly EventService $events,
        private readonly InvitationService $invitations,
        private readonly ParticipantService $participants,
    ) {}

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function show(string $slug): JsonResponse
    {
        return ApiResponse::success(
            EventResource::make($this->events->findBySlug($slug)),
            'Detail event berhasil diambil'
        );
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function formOptions(string $slug): JsonResponse
    {
        $this->events->findBySlug($slug);

        return ApiResponse::success(
            ['departments' => $this->participants->departmentOptions()],
            'Pilihan departemen dan jabatan berhasil diambil'
        );
    }

    #[Response(status: 403, description: 'BADGE tidak terdaftar dalam daftar undangan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function check(CheckInvitationRequest $request, string $slug): JsonResponse
    {
        $invitation = $this->invitations->check($slug, $request->validated('badgeId'));

        return ApiResponse::success(
            [
                'badgeId' => $invitation->user->badge_id,
                'confirmationStatus' => $invitation->confirmation_status->value,
                'confirmedAt' => $invitation->confirmed_at?->utc()->toIso8601String(),
            ],
            'BADGE terdaftar dalam daftar undangan'
        );
    }

    #[Response(status: 403, description: 'BADGE tidak terdaftar dalam daftar undangan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 409, description: 'Undangan sudah dikonfirmasi atau kuota penuh', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 422, description: 'Data peserta tidak sesuai dengan data karyawan', type: 'array{success: false, message: string, data: null}')]
    public function confirm(ConfirmInvitationRequest $request, string $slug): JsonResponse
    {
        $invitation = $this->invitations->confirm(
            $slug,
            $request->validated('badgeId'),
            $request->identity(),
            $request->attending()
        );

        return ApiResponse::success(
            InvitationResource::make($invitation),
            'Konfirmasi kehadiran berhasil disimpan'
        );
    }
}
