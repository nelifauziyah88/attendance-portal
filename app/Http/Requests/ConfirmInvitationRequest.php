<?php

// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;

// class ConfirmInvitationRequest extends FormRequest
// {
//     public function authorize(): bool
//     {
//         return true;
//     }

//     public function rules(): array
//     {
//         return [
//             'attending' => ['required', 'boolean'],
//         ];
//     }

//     public function attributes(): array
//     {
//         return [
//             'attending' => 'attending',
//         ];
//     }

//     public function messages(): array
//     {
//         return [
//             'required' => ':attribute wajib diisi',
//             'boolean' => ':attribute harus bernilai true atau false',
//         ];
//     }

//     public function attending(): bool
//     {
//         return $this->boolean('attending');
//     }
// }
