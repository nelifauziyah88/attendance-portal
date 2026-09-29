<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventInvitationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'all' => ['sometimes', 'boolean'],
            'badgeIds' => ['required_unless:all,true', 'array', 'max:5000'],
            'badgeIds.*' => ['string', 'max:50', 'distinct'],
        ];
    }

    public function attributes(): array
    {
        return [
            'all' => 'all',
            'badgeIds' => 'badgeIds',
            'badgeIds.*' => 'badgeIds',
        ];
    }

    public function messages(): array
    {
        return [
            'badgeIds.required_unless' => 'badgeIds wajib diisi jika all tidak bernilai true',
            'array' => ':attribute harus berupa array',
            'boolean' => ':attribute harus bernilai true atau false',
            'string' => ':attribute harus berupa teks',
            'badgeIds.max' => ':attribute maksimal :max item',
            'badgeIds.*.max' => ':attribute maksimal :max karakter',
            'distinct' => ':attribute tidak boleh duplikat',
        ];
    }

    public function inviteAll(): bool
    {
        return $this->boolean('all');
    }

    public function badgeIds(): array
    {
        return array_map('trim', $this->validated('badgeIds') ?? []);
    }
}
