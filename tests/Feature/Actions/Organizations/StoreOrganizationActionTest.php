<?php

namespace Tests\Feature\Actions\Organizations;

use App\Actions\Organizations\StoreOrganizationAction;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreOrganizationActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_store_a_new_organization_with_wrong_parent_id(): void
    {
        $this->expectException(ModelNotFoundException::class);

        (new StoreOrganizationAction)([
            'parent_id' => 1,
            'title' => 'Organization',
            'vulgar' => 'Vulgar Organization',
        ]);

        $this->assertEquals(0, Organization::query()->count());
    }

    public function test_can_store_a_new_organization_without_parent_id(): void
    {
        (new StoreOrganizationAction)([
            'parent_id' => 0,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $this->assertEquals(1, Organization::query()->count());

        tap(Organization::query()->first(), function (Organization $organization) {
            $this->assertNull($organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }

    public function test_can_store_a_new_organization_with_parent_id(): void
    {
        $existOrganization = Organization::factory()->create();

        (new StoreOrganizationAction)([
            'parent_id' => $existOrganization->id,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $this->assertEquals(2, Organization::query()->count());

        tap(Organization::query()->latest('id')->first(), function (Organization $organization) use ($existOrganization) {
            $this->assertEquals($existOrganization->id, $organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }
}
