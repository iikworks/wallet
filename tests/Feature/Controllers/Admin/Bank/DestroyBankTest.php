<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DestroyBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_destroy_bank(): void
    {
        $bank = Bank::factory()->create();
        $response = $this->delete(route('banks.destroy', [
            'id' => $bank->id,
        ]));

        $response->assertRedirectToRoute('login');
    }

    public function test_not_admin_cant_destroy_bank(): void
    {
        $bank = Bank::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('banks.destroy', [
            'id' => $bank->id,
        ]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_not_admin_cant_destroy_not_exist_bank(): void
    {
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('banks.destroy', [
            'id' => 1,
        ]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_not_admin_can_destroy_bank(): void
    {
        $bank = Bank::factory()->create();
        $user = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('banks.destroy', [
            'id' => $bank->id,
        ]));

        $response->assertRedirectToRoute('banks');
        $this->assertEquals(0, Bank::query()->count());
    }
}
