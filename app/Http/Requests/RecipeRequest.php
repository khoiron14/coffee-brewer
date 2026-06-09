<?php

namespace App\Http\Requests;

use App\Enums\GrindSize;
use App\Enums\PourType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecipeRequest extends FormRequest
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
            'brewer_id' => ['required', 'uuid', 'exists:brewers,id'],
            'coffee_id' => ['required', 'uuid', 'exists:coffees,id'],
            'name' => ['required', 'string', 'max:255'],
            'coffee_weight' => ['required', 'numeric', 'between:0,999.99'],
            'water_weight' => ['required', 'numeric', 'between:0,999.99'],
            'grind_size' => ['required', Rule::enum(GrindSize::class)],
            'temperature' => ['required', 'integer', 'min:0', 'max:150'],
            'total_duration' => ['required', 'integer', 'min:0'],
            'is_published' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],

            'steps' => ['required', 'array', 'min:1'],
            'steps.*.order' => ['required', 'integer', 'min:1'],
            'steps.*.pour_volume' => ['required', 'numeric', 'between:0,999.99'],
            'steps.*.pour_type' => ['required', Rule::enum(PourType::class)],
            'steps.*.duration' => ['required', 'integer', 'min:0'],
            'steps.*.note' => ['nullable', 'string'],
        ];
    }
}
