<?php

namespace App\Rules\Transactions;

use App\Models\Account;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class UserAccountId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Account::query()
            ->where('id', $value)
            ->where('user_id', request()->user()->id)
            ->first())
            $fail('validation.account_id')->translate();
    }
}
