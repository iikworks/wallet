<?php

namespace Tests\Feature\Controllers\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class UpdateOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_update_organization(): void
    {
        $organization = Organization::factory()->create();
        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_not_admin_cant_update_organization(): void
    {
        $organization = Organization::factory()->create();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]));

        $response->assertForbidden();
    }

    public function test_not_admin_cant_update_not_exist_organization(): void
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => 1,
        ]));

        $response->assertNotFound();
    }

    public function test_admin_can_update_organization_without_parent(): void
    {
        $organization = Organization::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'parent_id' => 0,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $organization->id,
                'title' => 'Organization',
                'vulgar_title' => 'Vulgar Organization',
                'children' => [],
                'created_at' => $organization->created_at->toIsoString(),
            ],
        ]);

        $this->assertEquals(1, Organization::query()->count());

        tap(Organization::query()->find($organization->id), function (Organization $organization) {
            $this->assertNull($organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }

    public function test_admin_can_update_organization_with_parent(): void
    {
        $organization = Organization::factory()->create();
        $existOrganization = Organization::factory()->create();

        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'parent_id' => $existOrganization->id,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $organization->id,
                'title' => 'Organization',
                'vulgar_title' => 'Vulgar Organization',
                'children' => [],
                'created_at' => $organization->created_at->toIsoString(),
            ],
        ]);

        $this->assertEquals(2, Organization::query()->count());

        tap(Organization::query()->find($organization->id), function (Organization $organization) use ($existOrganization) {
            $this->assertEquals($existOrganization->id, $organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }

    public function test_parent_id_field_must_be_exist_to_update_organization()
    {
        $organization = Organization::factory()->create();
//        $existOrganization = Organization::factory()->create();

        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'parent_id' => 1,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertJsonValidationErrorFor('parent_id');
    }

    public function test_title_field_must_be_string_to_update_organization()
    {
        $organization = Organization::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'title' => 500,
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    public function test_title_field_must_be_max_100_length_to_update_organization()
    {
        $organization = Organization::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'title' => Str::random(101),
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    public function test_vulgar_title_field_must_be_string_to_update_organization()
    {
        $organization = Organization::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'title' => 'Organization',
            'vulgar_title' => 500,
        ]);

        $response->assertJsonValidationErrorFor('vulgar_title');
    }

    public function test_vulgar_title_field_must_be_max_100_length_to_update_organization()
    {
        $organization = Organization::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('organizations.update', [
            'id' => $organization->id,
        ]), [
            'title' => 'Organization',
            'vulgar_title' => Str::random(101),
        ]);

        $response->assertJsonValidationErrorFor('vulgar_title');
    }
}
