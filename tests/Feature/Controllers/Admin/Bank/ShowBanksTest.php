<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ShowBanksTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_see_banks(): void
    {
        $response = $this->json(Request::METHOD_GET, route('banks'));

        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_banks(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $bank = Bank::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('banks'));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => [
                    'id' => $bank->id,
                    'title' => $bank->title,
                    'created_at' => $bank->created_at->toIsoString(),
                ],
            ],
        ]);
    }

    public function test_unauthorized_user_cant_see_bank(): void
    {
        $bank = Bank::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('banks.get-one', [
            'id' => $bank->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_bank(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $bank = Bank::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('banks.get-one', [
            'id' => $bank->id,
        ]));

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $bank->id,
                'title' => $bank->title,
                'created_at' => $bank->created_at->toIsoString(),
            ],
        ]);
    }
}
