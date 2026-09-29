<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(collect(['badgeId', 'name', 'department', 'position'])
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
            'department' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:150'],
            'attending' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'badgeId' => 'badgeId',
            'name' => 'name',
            'department' => 'department',
            'position' => 'position',
            'attending' => 'attending',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
            'boolean' => ':attribute harus bernilai true atau false',
        ];
    }

    public function identity(): array
    {
        return [
            'name' => $this->validated('name'),
            'department' => $this->validated('department'),
            'position' => $this->validated('position'),
        ];
    }

    public function attending(): bool
    {
        return $this->boolean('attending');
    }
}
