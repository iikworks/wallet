<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class UpdateBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_update_bank(): void
    {
        $bank = Bank::factory()->create();
        $response = $this->json(Request::METHOD_PATCH, route('banks.update', [
            'id' => $bank->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_not_admin_cant_update_bank(): void
    {
        $bank = Bank::factory()->create();
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('banks.update', [
            'id' => $bank->id,
        ]));

        $response->assertForbidden();
    }

    public function test_not_admin_cant_update_not_exist_bank(): void
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('banks.update', [
            'id' => 1,
        ]));

        $response->assertNotFound();
    }

    public function test_admin_can_update_bank(): void
    {
        $bank = Bank::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('banks.update', [
            'id' => $bank->id,
        ]), [
            'title' => 'Bank',
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $bank->id,
                'title' => 'Bank',
                'created_at' => $bank->created_at->toIsoString(),
            ],
        ]);

        $this->assertEquals(1, Bank::query()->count());

        tap(Bank::query()->find($bank->id), function (Bank $bank) {
            $this->assertEquals('Bank', $bank->title);
        });
    }

    public function test_title_field_must_be_string_to_update_bank()
    {
        $bank = Bank::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('banks.update', [
            'id' => $bank->id,
        ]), [
            'title' => 500,
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    public function test_title_field_must_be_max_100_length_to_update_bank()
    {
        $bank = Bank::factory()->create();
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_PATCH, route('banks.update', [
            'id' => $bank->id,
        ]), [
            'title' => Str::random(101),
        ]);

        $response->assertJsonValidationErrorFor('title');
    }
}
