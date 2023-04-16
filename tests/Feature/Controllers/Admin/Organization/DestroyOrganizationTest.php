<?php

namespace Tests\Feature\Controllers\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DestroyOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_destroy_organization(): void
    {
        $organization = Organization::factory()->create();
        $response = $this->delete(route('organizations.destroy', [
            'id' => $organization->id,
        ]));

        $response->assertRedirectToRoute('login');
    }

    public function test_not_admin_cant_destroy_organization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('organizations.destroy', [
            'id' => $organization->id,
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_not_admin_cant_destroy_not_exist_organization(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('organizations.destroy', [
            'id' => 1,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_not_admin_can_destroy_organization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('organizations.destroy', [
            'id' => $organization->id,
        ]));

        $response->assertRedirectToRoute('organizations');
        $this->assertEquals(0, Organization::query()->count());
    }
}
