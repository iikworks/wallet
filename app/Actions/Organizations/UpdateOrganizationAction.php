<?php

namespace App\Actions\Organizations;

use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

readonly class UpdateOrganizationAction
{
    public function __invoke(int $organizationId, array $data): Organization
    {
        if (isset($data['parent_id'])) {
            if (intval($data['parent_id']) != 0) {
                if (!Organization::query()->find($data['parent_id']))
                    throw new ModelNotFoundException("parent id not found");
            } else $data['parent_id'] = null;
        }

        if ($organizationId == $data['parent_id'])
            throw new InvalidArgumentException('Parent id cannot be yourself');

        $organization = Organization::query()->find($organizationId);
        if (!$organization)
            throw new ModelNotFoundException("organization id not found");

        $organization->fill($data);
        $organization->save();

        return $organization;
    }
}
