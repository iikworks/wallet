<?php

namespace App\View\Organizations;

use App\Actions\Organizations\ConvertToSelectAction as ConvertOrganizationsToSelectAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

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
            'title' => 'нет',
            'children' => collect(),
        ];

        unset($organizations[$organizationId]);

        $action = route('organizations.update', ['id' => $organization->id]);
        $parentId = $organization->parent_id ?: 0;

        return view('organizations.add', [
            'title' => __('organizations.editing'),
            'organization' => $organization,
            'organizations' => $organizations,
            'action' => $action,
            'parentId' => $parentId,
        ]);
    }
}
