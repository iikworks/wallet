<?php

namespace Tests\Feature\Controllers\Admin\Bank;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class StoreBankTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_store_a_new_bank(): void
    {
        $response = $this->json(Request::METHOD_POST, route('banks'));

        $response->assertUnauthorized();
    }

    public function test_not_admin_cant_store_a_new_bank(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json(Request::METHOD_POST, route('banks'));

        $response->assertForbidden();
    }

    public function test_admin_can_store_a_new_bank(): void
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_POST, route('banks'), [
            'title' => 'Bank',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'created_at',
            ],
        ]);
        $response->assertJson([
            'data' => [
                'title' => 'Bank',
            ],
        ]);

        $this->assertEquals(1, Bank::query()->count());

        tap(Bank::query()->first(), function (Bank $bank) {
            $this->assertEquals('Bank', $bank->title);
        });
    }

    public function test_title_field_required_to_store_a_new_bank()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_POST, route('banks'), [
//            'title' => 'Bank',
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    public function test_title_field_must_be_string_to_store_a_new_bank()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_POST, route('banks'), [
            'title' => 500,
        ]);

        $response->assertJsonValidationErrorFor('title');
    }

    public function test_title_field_must_be_max_100_length_to_store_a_new_bank()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'is_admin' => true,
            ]),
            ['*']
        );

        $response = $this->json(Request::METHOD_POST, route('banks'), [
            'title' => Str::random(101),
        ]);

        $response->assertJsonValidationErrorFor('title');
    }
}
