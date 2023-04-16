<?php

namespace Tests\Feature\Controllers\Admin\Organization;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreOrganizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_store_a_new_organization(): void
    {
        $response = $this->post(route('organizations'));

        $response->assertRedirectToRoute('login');
    }

    public function test_not_admin_cant_store_a_new_organization(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organizations'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_admin_can_store_a_new_organization_without_parent(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'parent_id' => 0,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertRedirectToRoute('organizations');

        $this->assertEquals(1, Organization::query()->count());

        tap(Organization::query()->first(), function (Organization $organization) {
            $this->assertNull($organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }

    public function test_admin_can_store_a_new_organization_with_parent(): void
    {
        $existOrganization = Organization::factory()->create();

        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'parent_id' => $existOrganization->id,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertRedirectToRoute('organizations');

        $this->assertEquals(2, Organization::query()->count());

        tap(Organization::query()->latest('id')->first(), function (Organization $organization) use ($existOrganization) {
            $this->assertEquals($existOrganization->id, $organization->parent_id);
            $this->assertEquals('Organization', $organization->title);
            $this->assertEquals('Vulgar Organization', $organization->vulgar_title);
        });
    }

    public function test_parent_id_field_must_be_numeric_to_store_a_new_organization()
    {
//        $existOrganization = Organization::factory()->create();

        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'parent_id' => "not numeric",
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertSessionHasErrorsIn('parent_id');
    }

    public function test_parent_id_field_must_be_exist_to_store_a_new_organization()
    {
//        $existOrganization = Organization::factory()->create();

        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'parent_id' => 1,
            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertSessionHasErrorsIn('parent_id');
    }

    public function test_title_field_required_to_store_a_new_organization()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
//            'title' => 'Organization',
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertSessionHasErrorsIn('title');
    }

    public function test_title_field_must_be_string_to_store_a_new_organization()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'title' => 500,
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertSessionHasErrorsIn('title');
    }

    public function test_title_field_must_be_max_100_length_to_store_a_new_organization()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'title' => Str::random(101),
            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertSessionHasErrorsIn('title');
    }

    public function test_vulgar_title_field_required_to_store_a_new_organization()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'title' => 'Organization',
//            'vulgar_title' => 'Vulgar Organization',
        ]);

        $response->assertSessionHasErrorsIn('vulgar_title');
    }

    public function test_vulgar_title_field_must_be_string_to_store_a_new_organization()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'title' => 'Organization',
            'vulgar_title' => 500,
        ]);

        $response->assertSessionHasErrorsIn('vulgar_title');
    }

    public function test_vulgar_title_field_must_be_max_100_length_to_store_a_new_organization()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('organizations'), [
            'title' => 'Organization',
            'vulgar_title' => Str::random(101),
        ]);

        $response->assertSessionHasErrorsIn('vulgar_title');
    }
}
