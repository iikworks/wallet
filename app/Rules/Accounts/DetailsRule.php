<?php

namespace App\Rules\Accounts;

use App\Models\Account;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\PotentiallyTranslatedString;
use Illuminate\Validation\Rule;

class DetailsRule implements ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param array<string, mixed> $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        switch (request()->input('type')) {
            case Account::BANK_ACCOUNT_TYPE:
                $validator = Validator::make($value, [
                    'account_number' => 'required|string|max:64',
                    'bank_id' => 'required|exists:banks,id',
                ]);
                if ($validator->fails()) $fail($validator->errors());

                break;

            case Account::CARD_TYPE:
                $validator = Validator::make($value, [
                    'card_number' => 'required|regex:/^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$/',
                    'card_holder' => 'required|string|uppercase|max:64',
                    'expires_at' => ['required', 'regex:/^(0[1-9]|1[0-2])\/[0-9]{2}$/'],
                    'system' => ['required', Rule::in(Account::SYSTEMS)],
                    'bank_id' => 'required|exists:banks,id',
                ]);
                if ($validator->fails()) $fail($validator->errors());

                break;
        }
    }
}
