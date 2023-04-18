<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_update_bank(): void
    {
        $bank = Bank::factory()->create();
        $response = $this->post(route('banks.update', [
            'id' => $bank->id,
        ]));

        $response->assertRedirectToRoute('login');
    }

    public function test_not_admin_cant_update_bank(): void
    {
        $bank = Bank::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('banks.update', [
            'id' => $bank->id,
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_not_admin_cant_update_not_exist_bank(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks.update', [
            'id' => 1,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_admin_can_update_bank(): void
    {
        $bank = Bank::factory()->create();
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($user)->post(route('banks.update', [
            'id' => $bank->id,
        ]), [
            'title' => 'Bank',
        ]);

        $this->assertEquals(1, Bank::query()->count());

        tap(Bank::query()->find($bank->id), function (Bank $bank) {
            $this->assertEquals('Bank', $bank->title);
        });
    }

    public function test_title_field_must_be_string_to_update_bank()
    {
        $bank = Bank::factory()->create();
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks.update', [
            'id' => $bank->id,
        ]), [
            'title' => 500,
        ]);

        $response->assertSessionHasErrorsIn('title');
    }

    public function test_title_field_must_be_max_100_length_to_update_bank()
    {
        $bank = Bank::factory()->create();
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->post(route('banks.update', [
            'id' => $bank->id,
        ]), [
            'title' => Str::random(101),
        ]);

        $response->assertSessionHasErrorsIn('title');
    }
}
