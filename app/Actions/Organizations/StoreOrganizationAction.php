<?php

namespace App\Actions\Organizations;

use App\Models\Organization;

readonly class StoreOrganizationAction
{
    public function __invoke(array $data): Organization
    {
        if (isset($data['parent_id']) && $data['parent_id'] != 0) {
            Organization::query()->findOrFail($data['parent_id']);
        } else $data['parent_id'] = null;

        $organization = new Organization();
        $organization->fill($data);
        $organization->save();

        return $organization;
    }
}
