<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordAnswerRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'correct' => ['required', 'boolean'],
            'good' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:0'],
            'type' => ['nullable', 'string', 'exists:character_sets,slug'],
            'character' => ['nullable', 'string', 'max:10'],
        ];
    }
}
