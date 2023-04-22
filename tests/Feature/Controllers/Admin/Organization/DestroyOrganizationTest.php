<?php

namespace Tests\Feature\Controllers\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class DestroyOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_destroy_organization(): void
    {
        $organization = Organization::factory()->create();
        $response = $this->json(Request::METHOD_DELETE, route('organizations.destroy', [
            'id' => $organization->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_not_admin_cant_destroy_organization(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $organization = Organization::factory()->create();

        $response = $this->json(Request::METHOD_DELETE, route('organizations.destroy', [
            'id' => $organization->id,
        ]));

        $response->assertForbidden();
    }

    public function test_not_admin_cant_destroy_not_exist_organization(): void
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_DELETE, route('organizations.destroy', [
            'id' => 1,
        ]));

        $response->assertNotFound();
    }

    public function test_admin_can_destroy_organization(): void
    {
        $organization = Organization::factory()->create();

        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_DELETE, route('organizations.destroy', [
            'id' => $organization->id,
        ]));

        $response->assertOk();
        $this->assertEquals(0, Organization::query()->count());
    }
}
