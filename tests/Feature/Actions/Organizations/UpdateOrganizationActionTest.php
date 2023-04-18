<?php

namespace Tests\Feature\Actions\Organizations;

use App\Actions\Organizations\UpdateOrganizationAction;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrganizationActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_update_not_exist_organization(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new UpdateOrganizationAction)(1, [
            'parent_id' => 1,
            'title' => 'Organization',
            'vulgar' => 'Vulgar Organization',
        ]);
    }

    public function test_cant_update_organization_with_wrong_parent_id(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $organization = Organization::factory()->create();

        (new UpdateOrganizationAction())($organization->id, [
            'parent_id' => 2,
            'title' => 'Organization',
            'vulgar' => 'Vulgar Organization',
        ]);
    }

    public function test_can_update_organization_without_parent_id(): void
    {
        $organization = Organization::factory()->create();

        (new UpdateOrganizationAction)($organization->id, [
            'parent_id' => 0,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        tap(Organization::query()->first(), function (Organization $organization) {
            $this->assertNull($organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }

    public function test_can_update_organization_with_parent_id(): void
    {
        $organization = Organization::factory()->create();
        $existOrganization = Organization::factory()->create();

        (new UpdateOrganizationAction())($organization->id, [
            'parent_id' => $existOrganization->id,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        tap(Organization::query()->find($organization->id), function (Organization $organization) use ($existOrganization) {
            $this->assertEquals($existOrganization->id, $organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }
}
