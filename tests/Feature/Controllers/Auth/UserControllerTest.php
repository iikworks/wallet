<?php

namespace Tests\Feature\Controllers\Auth;

use App\Actions\Auth\AuthenticateUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_see_authenticated_user()
    {
        $response = $this->json(Request::METHOD_GET, route('user'), headers: [
            'Authorization' => 'Bearer bad',
        ]);

        $response->assertUnauthorized();
    }

    public function test_can_see_authenticated_user()
    {
        $this->seed();
        $user = User::factory()->create();
        $token = (new AuthenticateUserAction)($user);

        $response = $this->json(Request::METHOD_GET, route('user'), headers: [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'is_admin' => $user->is_admin,
                'created_at' => $user->created_at->toISOString(),
            ],
        ]);
    }
}
