<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmInvitationRequest;
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

    #[Response(status: 403, description: 'Kode undangan tidak terdaftar', type: 'array{success: false, message: string, data: null}')]
    public function show(string $code): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::make($this->invitations->findByCode($code)),
            'Undangan berhasil diambil'
        );
    }

    #[Response(status: 403, description: 'Kode undangan tidak terdaftar', type: 'array{success: false, message: string, data: null}')]
    public function qr(string $code): HttpResponse
    {
        $invitation = $this->invitations->findByCode($code);

        return response($this->qrCodes->png($this->invitations->invitationUrl($invitation->code)), 200, [
            'Content-Type' => 'image/png',
        ]);
    }

    #[Response(status: 403, description: 'Kode undangan tidak terdaftar', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 409, description: 'Undangan sudah dikonfirmasi atau kuota penuh', type: 'array{success: false, message: string, data: null}')]
    public function confirm(ConfirmInvitationRequest $request, string $code): JsonResponse
    {
        return ApiResponse::success(
            InvitationResource::make($this->invitations->confirm($code, $request->attending())),
            'Konfirmasi kehadiran berhasil disimpan'
        );
    }
}
