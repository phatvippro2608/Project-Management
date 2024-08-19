<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreCardRequest extends FormRequest
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
            'content' => ['required', 'min:10'],
        ];
    }

    public function prepareForValidation()
    {
        $position = DB::table('cards')->where('column_id',  $this->column?->id)->orderBy('position')->first();
        return $this->merge([
            'column_id' => $this->column?->id,
            'position' => $position ? $position->position + 1000: 1000,
        ]);
    }
}
