<?php

namespace App\Http\Requests;

use App\Enums\ConfirmationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexEventInvitationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', Rule::enum(ConfirmationStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'status.enum' => 'status harus salah satu dari PENDING, HADIR, TIDAK_HADIR',
        ];
    }

    public function status(): ?ConfirmationStatus
    {
        return ConfirmationStatus::tryFrom((string) $this->validated('status'));
    }
}
