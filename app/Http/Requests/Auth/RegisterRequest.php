<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone' => 'required|string|regex:/^\+[0-9]{3} [0-9]{2} [0-9]{7}$/',
            'first_name' => 'required|min:2|max:30|alpha',
            'last_name' => 'required|min:2|max:30|alpha',
            'password' => 'required|min:6',
        ];
    }
}
