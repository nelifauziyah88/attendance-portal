<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Resources\ParticipantResource;
use App\Http\Responses\ApiResponse;
use App\Services\ParticipantService;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;

class ParticipantController extends Controller
{
    public function __construct(private readonly ParticipantService $participants) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            ParticipantResource::collection($this->participants->list()),
            'Daftar peserta berhasil diambil'
        );
    }

    #[Response(status: 409, description: 'BADGE sudah terdaftar', type: 'array{success: false, message: string, data: null}')]
    public function store(StoreParticipantRequest $request): JsonResponse
    {
        $participant = $this->participants->create($request->validated());

        return ApiResponse::success(
            ParticipantResource::make($participant),
            'Peserta berhasil didaftarkan',
            201
        );
    }
}
