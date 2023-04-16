<?php

namespace App\Actions\Organizations;

use App\Models\Organization;

readonly class DestroyOrganizationAction
{
    public function __invoke(int $organizationId): void
    {
        Organization::query()->findOrFail($organizationId)->delete();
    }
}
