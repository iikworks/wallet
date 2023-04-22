<?php

namespace Tests\Feature\Controllers\Account;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ShowAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_see_accounts(): void
    {
        $response = $this->json(Request::METHOD_GET, route('accounts'));
        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_accounts(): void
    {
        $this->seed();
        
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $account = Account::factory()->create();
        $response = $this->json(Request::METHOD_GET, route('accounts'));

        $details = $account->details;

        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => [
                    'id' => $account->id,
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'phone' => $user->phone,
                        'is_admin' => false,
                        'created_at' => $user->created_at->toIsoString(),
                    ],
                    'balance' => $account->balance / 100,
                    'currency' => $account->currency,
                    'details' => $details ? $details->toArray() : null,
                    'created_at' => $account->created_at->toIsoString(),
                ],
            ],
        ]);
    }

    public function test_unauthorized_user_cant_see_account(): void
    {
        $account = Account::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('accounts.get-one', [
            'id' => $account->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_user_cant_see_other_user_accounts(): void
    {
        $account = Account::factory()->create();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('accounts', [
            'id' => $account->id,
        ]));

        $response->assertOk();
        $response->assertJson([
            'data' => [],
        ]);
    }

    public function test_user_cant_see_other_user_account(): void
    {
        $account = Account::factory()->create();

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('accounts.get-one', [
            'id' => $account->id,
        ]));

        $response->assertNotFound();
    }

    public function test_authorized_user_can_see_account(): void
    {
        $this->seed();
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $account = Account::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('accounts.get-one', [
            'id' => $account->id,
        ]));

        $details = $account->details;

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $account->id,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                    'is_admin' => false,
                    'created_at' => $user->created_at->toIsoString(),
                ],
                'balance' => $account->balance / 100,
                'currency' => $account->currency,
                'details' => $details ? $details->toArray() : null,
                'created_at' => $account->created_at->toIsoString(),
            ],
        ]);
    }
}
