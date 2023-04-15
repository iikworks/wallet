<?php

namespace App\Http\Requests\Accounts;

use App\Models\Account;
use App\Rules\Accounts\DetailsRule;
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
            'balance' => 'required|numeric|min:0.00',
            'currency' => ['required', Rule::in(array_keys(config('constants.currencies')))],
            'type' => ['required', Rule::in([
                Account::CASH_TYPE,
                Account::CARD_TYPE,
                Account::BANK_ACCOUNT_TYPE
            ])],
            'details' => [new DetailsRule],
        ];
    }
}
