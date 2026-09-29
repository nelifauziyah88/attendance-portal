<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Http\Responses\ApiResponse;
use App\Services\InvitationService;
use App\Services\QrCodeService;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitations,
        private readonly QrCodeService $qrCodes,
    ) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::collection($this->invitations->list()),
            'Daftar undangan berhasil diambil'
        );
    }

    #[Response(status: 404, description: 'Peserta tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 409, description: 'Undangan sudah dibuat', type: 'array{success: false, message: string, data: null}')]
    public function store(StoreInvitationRequest $request): JsonResponse
    {
        $invitation = $this->invitations->create($request->validated('badgeId'));

        return ApiResponse::success(
            InvitationResource::make($invitation),
            'Undangan berhasil dibuat',
            201
        );
    }

    public function quota(): JsonResponse
    {
        return ApiResponse::success($this->invitations->quota(), 'Kuota undangan berhasil diambil');
    }

    #[Response(status: 403, description: 'BADGE tidak terdaftar dalam daftar undangan', type: 'array{success: false, message: string, data: null}')]
    public function show(string $badgeId): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::make($this->invitations->findByBadge($badgeId)),
            'Undangan berhasil diambil'
        );
    }

    #[Response(status: 403, description: 'BADGE tidak terdaftar dalam daftar undangan', type: 'array{success: false, message: string, data: null}')]
    public function qr(string $badgeId): HttpResponse
    {
        $this->invitations->findByBadge($badgeId);

        return response($this->qrCodes->png($this->invitations->invitationUrl($badgeId)), 200, [
            'Content-Type' => 'image/png',
        ]);
    }

    #[Response(status: 403, description: 'BADGE tidak terdaftar dalam daftar undangan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 422, description: 'Peserta belum memiliki email', type: 'array{success: false, message: string, data: null}')]
    public function sendEmail(string $badgeId): JsonResponse
    {
        $invitation = $this->invitations->sendEmail($badgeId);

        return ApiResponse::success(
            InvitationResource::make($invitation),
            "Undangan berhasil dikirim ke {$invitation->user->email}"
        );
    }

    #[Response(status: 403, description: 'BADGE tidak terdaftar dalam daftar undangan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 409, description: 'Undangan sudah dikonfirmasi atau kuota penuh', type: 'array{success: false, message: string, data: null}')]
    public function confirm(ConfirmInvitationRequest $request, string $badgeId): JsonResponse
    {
        $invitation = $this->invitations->confirm($badgeId, $request->attending());

        return ApiResponse::success(
            InvitationResource::make($invitation),
            'Konfirmasi kehadiran berhasil disimpan'
        );
    }
}
