<?php

namespace App\View\Organizations;

use App\Actions\Organizations\ConvertToSelectAction as ConvertOrganizationsToSelectAction;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class AddView
{
    public function __invoke(User $user): View|Application|Factory
    {
        $organizations = (new ConvertOrganizationsToSelectAction)(Organization::query()
            ->where('parent_id', null)
            ->with('childrenRecursive')
            ->get());

        $organizations[0] = [
            'title' => 'нет',
            'children' => collect(),
        ];

        $action = route('organizations');
        $parentId = 0;

        return view('organizations.add', [
            'title' => __('organizations.adding'),
            'organization' => null,
            'organizations' => $organizations,
            'action' => $action,
            'parentId' => $parentId,
        ]);
    }
}
