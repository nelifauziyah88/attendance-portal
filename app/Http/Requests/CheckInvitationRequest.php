<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('badgeId'))) {
            $this->merge(['badgeId' => trim($this->input('badgeId'))]);
        }
    }

    public function rules(): array
    {
        return [
            'badgeId' => ['required', 'string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'badgeId' => 'badgeId',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
        ];
    }
}
