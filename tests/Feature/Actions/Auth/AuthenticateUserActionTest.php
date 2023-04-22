<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\AuthenticateUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthenticateUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticate(): void
    {
        $user = User::factory()->create();
        (new AuthenticateUserAction)($user);

        $this->assertEquals(1, PersonalAccessToken::query()->count());
        tap(PersonalAccessToken::query()->first(), function (PersonalAccessToken $personalAccessToken) use ($user) {
            $this->assertEquals($user->id, $personalAccessToken->tokenable_id);
        });
    }
}
