<?php

namespace App\View\Organizations;

use App\Models\Organization;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class DeleteView
{
    public function __invoke(int $organizationId): View|Application|Factory
    {
        $organization = Organization::query()->findOrFail($organizationId);

        return view('organizations.delete', [
            'title' => __('organizations.deleting'),
            'organization' => $organization,
        ]);
    }
}
