<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(collect(['slug', 'name', 'description', 'location', 'eventDate', 'startTime', 'endTime'])
            ->filter(fn (string $field) => is_string($this->input($field)))
            ->mapWithKeys(fn (string $field) => [$field => trim($this->input($field))])
            ->map(fn (string $value) => $value === '' ? null : $value)
            ->all());

        if (is_string($this->input('slug'))) {
            $this->merge(['slug' => strtolower($this->input('slug'))]);
        }
    }

    public function rules(): array
    {
        return [
            'slug' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('events', 'slug')->ignore($this->route('eventId')),
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:5000'],
            'location' => ['required', 'string', 'max:200'],
            'eventDate' => ['required', 'date_format:Y-m-d'],
            'startTime' => ['required', 'date_format:H:i'],
            'endTime' => ['nullable', 'date_format:H:i', 'after:startTime'],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'slug' => 'slug',
            'name' => 'name',
            'description' => 'description',
            'location' => 'location',
            'eventDate' => 'eventDate',
            'startTime' => 'startTime',
            'endTime' => 'endTime',
            'capacity' => 'capacity',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi',
            'string' => ':attribute harus berupa teks',
            'max' => ':attribute maksimal :max karakter',
            'capacity.max' => ':attribute maksimal :max',
            'slug.regex' => ':attribute hanya boleh berisi huruf kecil, angka, dan tanda strip',
            'slug.unique' => ':attribute sudah digunakan oleh event lain',
            'eventDate.date_format' => ':attribute harus berformat YYYY-MM-DD',
            'date_format' => ':attribute harus berformat HH:MM',
            'endTime.after' => ':attribute harus setelah startTime',
            'integer' => ':attribute harus berupa angka bulat',
            'min' => ':attribute minimal :min',
        ];
    }

    public function toAttributes(): array
    {
        return [
            'slug' => $this->validated('slug'),
            'name' => $this->validated('name'),
            'description' => $this->validated('description'),
            'location' => $this->validated('location'),
            'event_date' => $this->validated('eventDate'),
            'start_time' => $this->validated('startTime'),
            'end_time' => $this->validated('endTime'),
            'capacity' => $this->validated('capacity') ?? (int) config('invitation.capacity'),
        ];
    }
}
