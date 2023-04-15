<?php

namespace App\Http\Requests\Transactions;

use App\Models\Transaction;
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
            'amount' => 'required|numeric|min:0.01',
            'type' => ['required', Rule::in([
                Transaction::EXPENSE_TYPE,
                Transaction::REPLENISHMENT_TYPE,
            ])],
            'date' => 'required|date_format:Y-m-d\TH:i',
        ];
    }
}
