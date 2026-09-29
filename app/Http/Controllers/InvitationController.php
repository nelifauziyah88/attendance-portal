<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmInvitationRequest;
use App\Http\Requests\StoreInvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Http\Responses\ApiResponse;
use App\Services\InvitationService;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitations,
        private readonly QrCodeService $qrCodes,
    ) {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::collection($this->invitations->list())->resolve(),
            'Daftar undangan berhasil diambil'
        );
    }

    public function store(StoreInvitationRequest $request): JsonResponse
    {
        $invitation = $this->invitations->create($request->validated('badgeId'));

        return ApiResponse::success(
            InvitationResource::make($invitation)->resolve(),
            'Undangan berhasil dibuat',
            201
        );
    }

    public function quota(): JsonResponse
    {
        return ApiResponse::success($this->invitations->quota(), 'Kuota undangan berhasil diambil');
    }

    public function show(string $badgeId): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::make($this->invitations->findByBadge($badgeId))->resolve(),
            'Undangan berhasil diambil'
        );
    }

    public function qr(string $badgeId): Response
    {
        $this->invitations->findByBadge($badgeId);

        return response($this->qrCodes->png($this->invitations->invitationUrl($badgeId)), 200, [
            'Content-Type' => 'image/png',
        ]);
    }

    public function confirm(ConfirmInvitationRequest $request, string $badgeId): JsonResponse
    {
        $invitation = $this->invitations->confirm($badgeId, $request->attending());

        return ApiResponse::success(
            InvitationResource::make($invitation)->resolve(),
            'Konfirmasi kehadiran berhasil disimpan'
        );
    }
}
