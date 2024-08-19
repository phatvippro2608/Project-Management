<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CardsReorderUpdateRequest extends FormRequest
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
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'columns' => ['nullable', 'array'],
            'columns.*.id' => ['integer', 'required', 'exists:\App\Models\Column,id'],
            'columns.*.cards' => ['nullable', 'array'],
            'columns.*.cards.*.id' => ['nullable', 'integer', 'required_with:columns.*.cards', 'exists:\App\Models\Card,id'],
            'columns.*.cards.*.position' => ['nullable', 'required_with:columns.*.cards', 'numeric'],
        ];
    }
}
