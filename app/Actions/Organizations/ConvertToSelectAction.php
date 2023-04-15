<?php

namespace App\Actions\Organizations;

use Illuminate\Support\Collection;

readonly class ConvertToSelectAction
{
    public function __invoke(Collection $organizations): Collection
    {
        $organizationsForSelect = collect();

        foreach ($organizations as $organization) {
            $organizationsForSelect[$organization->id] = [
                'title' => $organization->title,
                'children' => $this->convertChildren($organization->children),
            ];
        }

        return $organizationsForSelect;
    }

    public function convertChildren(Collection $children): Collection
    {
        $childrenForSelect = collect();

        foreach ($children as $child) {
            $childrenForSelect[$child->id] = [
                'title' => $child->title,
                'children' => $this->convertChildren($child->children),
            ];
        }

        return $childrenForSelect;
    }
}
