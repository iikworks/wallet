<?php

namespace App\View\Organizations;

use App\Actions\Organizations\ConvertToSelectAction as ConvertOrganizationsToSelectAction;
use App\Models\Organization;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class AddView
{
    public function __invoke(): View|Application|Factory
    {
        $organizations = (new ConvertOrganizationsToSelectAction)(Organization::query()
            ->where('parent_id', null)
            ->with('childrenRecursive')
            ->get());

        $organizations[0] = [
            'title' => '&nbsp;',
            'subtitle' => __('main.no'),
            'children' => collect(),
        ];

        $action = route('organizations');
        $parentId = 0;

        return view('organizations.add', [
            'title' => __('organizations.adding'),
            'organization' => null,
            'organizations' => $organizations->toArray(),
            'action' => $action,
            'parentId' => $parentId,
        ]);
    }
}
