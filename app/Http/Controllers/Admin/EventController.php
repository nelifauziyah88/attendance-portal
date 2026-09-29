<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Responses\ApiResponse;
use App\Services\EventService;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(private readonly EventService $events) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success(
            EventResource::collection($this->events->list()),
            'Daftar event berhasil diambil'
        );
    }

    public function store(EventRequest $request): JsonResponse
    {
        return ApiResponse::success(
            EventResource::make($this->events->create($request->toAttributes())),
            'Event berhasil dibuat',
            201
        );
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function show(int $eventId): JsonResponse
    {
        return ApiResponse::success(
            EventResource::make($this->events->find($eventId)),
            'Detail event berhasil diambil'
        );
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    #[Response(status: 409, description: 'Kapasitas kurang dari jumlah peserta yang sudah konfirmasi hadir', type: 'array{success: false, message: string, data: null}')]
    public function update(EventRequest $request, int $eventId): JsonResponse
    {
        return ApiResponse::success(
            EventResource::make($this->events->update($eventId, $request->toAttributes())),
            'Event berhasil diperbarui'
        );
    }

    #[Response(status: 404, description: 'Event tidak ditemukan', type: 'array{success: false, message: string, data: null}')]
    public function destroy(int $eventId): JsonResponse
    {
        $this->events->delete($eventId);

        return ApiResponse::success(null, 'Event berhasil dihapus');
    }
}
