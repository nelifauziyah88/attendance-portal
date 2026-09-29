<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(collect(['badgeId', 'name', 'department', 'position', 'email'])
            ->filter(fn (string $field) => is_string($this->input($field)))
            ->mapWithKeys(fn (string $field) => [$field => trim($this->input($field))])
            ->map(fn (string $value) => $value === '' ? null : $value)
            ->all());
    }

    public function rules(): array
    {
        return [
            'badgeId' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:150'],
            'department' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'string', 'email', 'max:150'],
        ];
    }

    public function attributes(): array
    {
        return [
            'badgeId' => 'badgeId',
            'name' => 'name',
            'department' => 'department',
            'position' => 'position',
            'email' => 'email',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
            'email' => ':attribute harus berupa alamat email yang valid',
        ];
    }
}
