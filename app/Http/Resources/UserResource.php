<?php

namespace App\Http\Resources;

use App\Actions\Accounts\ConvertCurrencyAction;
use App\Actions\Users\CalculateAccountsBalanceAction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('accounts');

        $balance = (new CalculateAccountsBalanceAction(
            new ConvertCurrencyAction(),
        ))($this->accounts);

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'is_admin' => $this->is_admin,
            'balance' => $balance,
            'currency' => config('app.currency'),
            'created_at' => $this->created_at->toIsoString(),
        ];
    }
}
