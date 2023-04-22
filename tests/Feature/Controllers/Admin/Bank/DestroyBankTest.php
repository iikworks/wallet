<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class DestroyBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_destroy_bank(): void
    {
        $bank = Bank::factory()->create();
        $response = $this->json(Request::METHOD_DELETE, route('banks.destroy', [
            'id' => $bank->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_not_admin_cant_destroy_bank(): void
    {
        $bank = Bank::factory()->create();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json(Request::METHOD_DELETE, route('banks.destroy', [
            'id' => $bank->id,
        ]));

        $response->assertForbidden();
    }

    public function test_not_admin_cant_destroy_not_exist_bank(): void
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_DELETE, route('banks.destroy', [
            'id' => 1,
        ]));

        $response->assertNotFound();
    }

    public function test_not_admin_can_destroy_bank(): void
    {
        $bank = Bank::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_DELETE, route('banks.destroy', [
            'id' => $bank->id,
        ]));

        $response->assertOk();
        $this->assertEquals(0, Bank::query()->count());
    }
}
