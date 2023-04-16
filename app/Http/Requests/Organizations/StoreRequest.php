<?php

namespace App\Http\Requests\Organizations;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'parent_id' => 'numeric',
            'title' => 'required|string|max:100',
            'vulgar_title' => 'required|string|max:100',
        ];
    }
}
