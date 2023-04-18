<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StoreBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_store_a_new_bank(): void
    {
        $response = $this->post(route('banks'));

        $response->assertRedirectToRoute('login');
    }

    public function test_not_admin_cant_store_a_new_bank(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('banks'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_admin_can_store_a_new_bank(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks'), [
            'title' => 'Bank',
        ]);

        $response->assertRedirectToRoute('banks');

        $this->assertEquals(1, Bank::query()->count());

        tap(Bank::query()->first(), function (Bank $bank) {
            $this->assertEquals('Bank', $bank->title);
        });
    }

    public function test_title_field_required_to_store_a_new_bank()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks'), [
//            'title' => 'Bank',
        ]);

        $response->assertSessionHasErrorsIn('title');
    }

    public function test_title_field_must_be_string_to_store_a_new_bank()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks'), [
            'title' => 500,
        ]);

        $response->assertSessionHasErrorsIn('title');
    }

    public function test_title_field_must_be_max_100_length_to_store_a_new_bank()
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks'), [
            'title' => Str::random(101),
        ]);

        $response->assertSessionHasErrorsIn('title');
    }
}
