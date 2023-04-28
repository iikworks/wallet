<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $details = $this->details;

        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'currency' => $this->currency,
            'balance' => normalize_number($this->balance),
            'type' => $this->type,
            'details' => $details != null ? $details->toArray() : null,
            'created_at' => $this->created_at->toIsoString(),
        ];
    }
}
