<?php

namespace App\View\Organizations;

use App\Actions\Organizations\ConvertToSelectAction as ConvertOrganizationsToSelectAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class EditView
{
    public function __invoke(User $user, int $organizationId): View|Application|Factory
    {
        $organization = Organization::query()->findOrFail($organizationId);

        $organizations = (new ConvertOrganizationsToSelectAction)(Organization::query()
            ->where('parent_id', null)
            ->with('childrenRecursive')
            ->get());

        $organizations[0] = [
            'title' => '&nbsp;',
            'subtitle' => __('main.no'),
            'children' => collect(),
        ];

        $organizations = $this->deleteOrganizationByID($organizationId, $organizations);

        $action = route('organizations.update', ['id' => $organization->id]);
        $parentId = $organization->parent_id ?: 0;

        return view('organizations.add', [
            'title' => __('organizations.editing'),
            'organization' => $organization,
            'organizations' => $organizations->toArray(),
            'action' => $action,
            'parentId' => $parentId,
        ]);
    }

    public function deleteOrganizationByID(int $organizationId, Collection $organizations): Collection|null
    {
        return $this->forgetRecursive($organizations, $organizationId);
    }

    function forgetRecursive($collection, $key)
    {
        $collection->forget($key);

        $collection->transform(function ($value) use ($key) {
            if ($value instanceof Collection) {
                $this->forgetRecursive($value, $key);
            } elseif (is_array($value)) {
                $this->forgetRecursive(collect($value), $key);
            }

            return $value;
        });

        return $collection;
    }
}
