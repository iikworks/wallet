<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account' => new AccountResource($this->account),
            'organization' => new OrganizationResource($this->organization),
            'type' => $this->type,
            'amount' => normalize_number($this->amount),
            'date' => $this->date->toIsoString(),
            'created_at' => $this->created_at->toIsoString(),
        ];
    }
}
