<?php

// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;

// class SendInvitationEmailsRequest extends FormRequest
// {
//     public function authorize(): bool
//     {
//         return true;
//     }

//     public function rules(): array
//     {
//         return [
//             'badgeIds' => ['sometimes', 'array', 'max:5000'],
//             'badgeIds.*' => ['string', 'max:50'],
//             'resend' => ['sometimes', 'boolean'],
//         ];
//     }

//     public function attributes(): array
//     {
//         return [
//             'badgeIds' => 'badgeIds',
//             'badgeIds.*' => 'badgeIds',
//             'resend' => 'resend',
//         ];
//     }

//     public function messages(): array
//     {
//         return [
//             'array' => ':attribute harus berupa array',
//             'string' => ':attribute harus berupa teks',
//             'badgeIds.max' => ':attribute maksimal :max item',
//             'badgeIds.*.max' => ':attribute maksimal :max karakter',
//             'boolean' => ':attribute harus bernilai true atau false',
//         ];
//     }

//     public function badgeIds(): ?array
//     {
//         $badgeIds = $this->validated('badgeIds');

//         return $badgeIds === null ? null : array_map('trim', $badgeIds);
//     }

//     public function resend(): bool
//     {
//         return $this->boolean('resend');
//     }
// }
