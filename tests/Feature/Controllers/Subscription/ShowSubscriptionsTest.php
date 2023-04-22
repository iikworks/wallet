<?php

namespace Tests\Feature\Controllers\Subscription;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class ShowSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_see_subscriptions(): void
    {
        $response = $this->json(Request::METHOD_GET, route('subscriptions'));
        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_subscriptions(): void
    {
        $this->seed();

        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $subscription = Subscription::factory()->create();

        Sanctum::actingAs(
            $account->user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('subscriptions'));

        $accountDetails = $account->details;

        $response->assertOk();
        $response->assertJson([
            'data' => [
                0 => [
                    'id' => $subscription->id,
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
                    'amount' => $subscription->amount / 100,
                    'currency' => $subscription->currency,
                    'day' => $subscription->day,
                    'created_at' => $subscription->created_at->toIsoString(),
                ],
            ],
        ]);
    }

    public function test_unauthorized_user_cant_see_subscription(): void
    {
        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $subscription = Subscription::factory()->create();

        $response = $this->json(Request::METHOD_GET, route('subscriptions.get-one', [
            'id' => $subscription->id,
        ]));

        $response->assertUnauthorized();
    }

    public function test_authorized_user_can_see_subscription(): void
    {
        $this->seed();

        $account = Account::factory()->create();
        $organization = Organization::factory()->create();
        $subscription = Subscription::factory()->create();

        Sanctum::actingAs(
            $account->user,
            ['*']
        );

        $response = $this->json(Request::METHOD_GET, route('subscriptions.get-one', [
            'id' => $subscription->id,
        ]));

        $accountDetails = $account->details;

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $subscription->id,
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
                'amount' => $subscription->amount / 100,
                'currency' => $subscription->currency,
                'day' => $subscription->day,
                'created_at' => $subscription->created_at->toIsoString(),
            ],
        ]);
    }
}
