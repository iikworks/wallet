<?php

namespace App\Actions\Accounts;

use App\Models\Account;
use Illuminate\Support\Collection;

readonly class ConvertCardSystemsToSelectAction
{
    public function __invoke(Collection $systems): Collection
    {
        $systems = collect();

        foreach (Account::SYSTEMS as $system) {
            $systems[$system] = $system;
        }

        return $systems;
    }
}
