<?php

namespace App\Http\Requests\Subscriptions;

use App\Rules\Transactions\UserAccountId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'account_id' => ['required', new UserAccountId],
            'organization_id' => 'required|exists:organizations,id',
            'currency' => ['required', Rule::in(array_keys(config('constants.currencies')))],
            'amount' => 'required|numeric|min:0.01',
            'day' => 'required|numeric|min:1|max:31',
        ];
    }
}
