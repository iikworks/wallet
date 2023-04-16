<?php

namespace Tests\Feature\Actions\Organizations;

use App\Actions\Organizations\DestroyOrganizationAction;
use App\Models\Organization;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyOrganizationActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_destroy_not_exist_organization()
    {
        $this->expectException(ModelNotFoundException::class);

        (new DestroyOrganizationAction)(1);
    }

    public function test_can_destroy_organization()
    {
        $organization = Organization::factory()->create();

        (new DestroyOrganizationAction)($organization->id);

        $this->assertEquals(0, Organization::query()->count());
    }
}
