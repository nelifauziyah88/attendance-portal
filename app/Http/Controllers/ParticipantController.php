<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Resources\ParticipantResource;
use App\Http\Responses\ApiResponse;
use App\Services\ParticipantService;
use Illuminate\Http\JsonResponse;

class ParticipantController extends Controller
{
    public function __construct(private readonly ParticipantService $participants)
    {
    }

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            ParticipantResource::collection($this->participants->list())->resolve(),
            'Daftar peserta berhasil diambil'
        );
    }

    public function store(StoreParticipantRequest $request): JsonResponse
    {
        $participant = $this->participants->create($request->validated());

        return ApiResponse::success(
            ParticipantResource::make($participant)->resolve(),
            'Peserta berhasil didaftarkan',
            201
        );
    }
}
