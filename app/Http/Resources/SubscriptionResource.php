<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'amount' => normalize_number($this->amount),
            'currency' => $this->currency,
            'day' => $this->day,
            'created_at' => $this->created_at->toIsoString(),
        ];
    }
}
