<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexEventInvitationsRequest;
use App\Http\Requests\SendInvitationEmailsRequest;
use App\Http\Requests\StoreEventInvitationsRequest;
use App\Http\Resources\InvitationResource;
use App\Http\Responses\ApiResponse;
use App\Services\EventService;
use App\Services\InvitationService;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;

class EventInvitationController extends Controller
{
    public function __construct(
        private readonly EventService $events,
        private readonly InvitationService $invitations,
    ) {}

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function index(IndexEventInvitationsRequest $request, int $eventId): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::collection($this->invitations->listForEvent($eventId, $request->status())),
            'Daftar undangan berhasil diambil'
        );
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function store(StoreEventInvitationsRequest $request, int $eventId): JsonResponse
    {
        $result = $this->invitations->createForEvent($eventId, $request->inviteAll(), $request->badgeIds());

        return ApiResponse::success($result, "{$result['created']} undangan berhasil dibuat", $result['created'] > 0 ? 201 : 200);
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function sendEmails(SendInvitationEmailsRequest $request, int $eventId): JsonResponse
    {
        $result = $this->invitations->sendEmails($eventId, $request->badgeIds(), $request->resend());

        return ApiResponse::success($result, "{$result['sent']} email undangan berhasil dikirim");
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function quota(int $eventId): JsonResponse
    {
        return ApiResponse::success(
            $this->invitations->quota($this->events->find($eventId)),
            'Kuota event berhasil diambil'
        );
    }
}
