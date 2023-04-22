<?php

namespace App\Http\Resources;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class OrganizationResource extends JsonResource
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
            'title' => $this->title,
            'vulgar_title' => $this->vulgar_title,
            'children' => $this->getChildren(),
            'created_at' => $this->created_at->toIsoString(),
        ];
    }

    private function getChildren(): Collection
    {
        $children = collect();

        $this->childrenRecursive->each(function (Organization $organization) use ($children) {
            $child = new OrganizationResource($organization);
            $childChildren = $child->getChildren();
            $child->additional(['children' => $childChildren]);
            $children->add($child);
        });

        return $children;
    }
}

