<?php

namespace App\View\Organizations;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ListView
{
    public function __invoke(User $user, int $page): View|Application|Factory
    {
        $organizations = Organization::query()
            ->latest('created_at')
            ->with('childrenRecursive')
            ->paginate(50, page: $page);

        return view('organizations.list', [
            'title' => __('organizations.title'),
            'organizations' => $organizations,
        ]);
    }
}
