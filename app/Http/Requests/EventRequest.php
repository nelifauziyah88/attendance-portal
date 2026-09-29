<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(collect(['name', 'description', 'location', 'eventDate', 'startTime', 'endTime'])
            ->filter(fn (string $field) => is_string($this->input($field)))
            ->mapWithKeys(fn (string $field) => [$field => trim($this->input($field))])
            ->map(fn (string $value) => $value === '' ? null : $value)
            ->all());
    }

    public function rules(): array
    {
        return [
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
