<?php

namespace Tests\Feature\Controllers\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ShowOrganizationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_see_organizations(): void
    {
        $response = $this->json(Request::METHOD_GET, route('organizations'));

        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_organizations(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $organization = Organization::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('organizations'));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => [
                    'id' => $organization->id,
                    'title' => $organization->title,
                    'vulgar_title' => $organization->vulgar_title,
                    'children' => [],
                    'created_at' => $organization->created_at->toIsoString(),
                ],
            ],
        ]);
    }

    public function test_unauthorized_user_cant_see_organization(): void
    {
        $organization = Organization::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('organizations.get-one', [
            'id' => $organization->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_organization(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $organization = Organization::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('organizations.get-one', [
            'id' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $organization->id,
                'title' => $organization->title,
                'vulgar_title' => $organization->vulgar_title,
                'children' => [],
                'created_at' => $organization->created_at->toIsoString(),
            ],
        ]);
    }

    public function test_authorized_user_can_see_organization_children(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $organization = Organization::factory()->create();
        $childOrganization = Organization::factory()->create(['parent_id' => $organization->id]);
        $childChildOrganization = Organization::factory()->create(['parent_id' => $childOrganization->id]);

        $response = $this->json(Request::METHOD_GET, route('organizations.get-one', [
            'id' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $organization->id,
                'title' => $organization->title,
                'vulgar_title' => $organization->vulgar_title,
                'children' => [
                    0 => [
                        'id' => $childOrganization->id,
                        'title' => $childOrganization->title,
                        'vulgar_title' => $childOrganization->vulgar_title,
                        'children' => [
                            0 => [
                                'id' => $childChildOrganization->id,
                                'title' => $childChildOrganization->title,
                                'vulgar_title' => $childChildOrganization->vulgar_title,
                                'children' => [],
                                'created_at' => $childChildOrganization->created_at->toIsoString(),
                            ],
                        ],
                        'created_at' => $childOrganization->created_at->toIsoString(),
                    ],
                ],
                'created_at' => $organization->created_at->toIsoString(),
            ],
        ]);
    }
}
