<?php

namespace Tests\Feature\Controllers\Transaction;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ShowTransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_see_transactions(): void
    {
        $response = $this->json(Request::METHOD_GET, route('transactions'));
        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_transactions(): void
    {
        $this->seed();

        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $transaction = Transaction::factory()->create();

        Sanctum::actingAs(
            $account->user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('transactions'));

        $accountDetails = $account->details;

        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => [
                    'id' => $transaction->id,
                    'organization' => [
                        'id' => $organization->id,
                        'title' => $organization->title,
                        'vulgar_title' => $organization->vulgar_title,
                        'children' => [],
                        'created_at' => $organization->created_at->toIsoString(),
                    ],
                    'account' => [
                        'id' => $account->id,
                        'user' => [
                            'id' => $account->user->id,
                            'first_name' => $account->user->first_name,
                            'last_name' => $account->user->last_name,
                            'phone' => $account->user->phone,
                            'is_admin' => $account->user->is_admin,
                            'created_at' => $account->user->created_at->toIsoString(),
                        ],
                        'balance' => $account->balance / 100,
                        'currency' => $account->currency,
                        'details' => $accountDetails ? $accountDetails->toArray() : null,
                        'created_at' => $account->created_at->toIsoString(),
                    ],
                    'type' => $transaction->type,
                    'amount' => $transaction->amount / 100,
                    'date' => $transaction->date->toIsoString(),
                    'created_at' => $transaction->created_at->toIsoString(),
                ],
            ],
        ]);
    }

    public function test_unauthorized_user_cant_see_transaction(): void
    {
        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $transaction = Transaction::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('transactions.get-one', [
            'id' => $transaction->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_transaction(): void
    {
        $this->seed();
        
        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $transaction = Transaction::factory()->create();

        Sanctum::actingAs(
            $account->user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('transactions.get-one', [
            'id' => $transaction->id,
        ]));

        $accountDetails = $account->details;

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $transaction->id,
                'organization' => [
                    'id' => $organization->id,
                    'title' => $organization->title,
                    'vulgar_title' => $organization->vulgar_title,
                    'children' => [],
                    'created_at' => $organization->created_at->toIsoString(),
                ],
                'account' => [
                    'id' => $account->id,
                    'user' => [
                        'id' => $account->user->id,
                        'first_name' => $account->user->first_name,
                        'last_name' => $account->user->last_name,
                        'phone' => $account->user->phone,
                        'is_admin' => $account->user->is_admin,
                        'created_at' => $account->user->created_at->toIsoString(),
                    ],
                    'balance' => $account->balance / 100,
                    'currency' => $account->currency,
                    'details' => $accountDetails ? $accountDetails->toArray() : null,
                    'created_at' => $account->created_at->toIsoString(),
                ],
                'type' => $transaction->type,
                'amount' => $transaction->amount / 100,
                'date' => $transaction->date->toIsoString(),
                'created_at' => $transaction->created_at->toIsoString(),
            ],
        ]);
    }
}
