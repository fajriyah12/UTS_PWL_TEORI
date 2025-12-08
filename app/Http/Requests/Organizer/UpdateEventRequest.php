<?php

namespace App\Http\Requests\Organizer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'venue_id' => 'required|exists:venues,id',
            'image' => 'nullable|image|max:2048',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:draft,published',
            'ticket_types' => 'nullable|array',
            'ticket_types.*.id' => 'nullable|exists:ticket_types,id',
            'ticket_types.*.name' => 'required|string|max:100',
            'ticket_types.*.price' => 'required|integer|min:1000',
            'ticket_types.*.quota' => 'required|integer|min:1',
            'ticket_types.*.per_user_limit' => 'required|integer|min:1',
            'ticket_types.*.sales_start' => 'nullable|date',
            'ticket_types.*.sales_end' => 'nullable|date|after:ticket_types.*.sales_start',
        ];
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'Waktu berakhir harus setelah waktu mulai.',
            'ticket_types.*.price.min' => 'Harga minimal Rp 1.000.',
            'ticket_types.*.quota.min' => 'Kuota minimal 1 tiket.',
        ];
    }
}
