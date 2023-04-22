<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\AuthenticateUserAction;
use App\Actions\Auth\AuthenticateUserByCredentialsAction;
use App\Actions\Users\FormatNumberFromIMaskAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthenticateUserByCredentialsActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cant_authorize_with_wrong_phone(): void
    {
        $this->expectException(UnauthorizedException::class);

        User::factory()->create([
            'phone' => '+375222342525',
        ]);

        (new AuthenticateUserByCredentialsAction(
            new FormatNumberFromIMaskAction(),
            new AuthenticateUserAction(),
        ))([
            'phone' => 'wrong',
            'password' => 'password'
        ]);
    }

    public function test_cant_authorize_with_wrong_password(): void
    {
        $this->expectException(UnauthorizedException::class);

        User::factory()->create([
            'phone' => '+375222342525',
        ]);

        (new AuthenticateUserByCredentialsAction(
            new FormatNumberFromIMaskAction(),
            new AuthenticateUserAction(),
        ))([
            'phone' => '+375 22 2342525',
            'password' => 'wrong'
        ]);
    }

    public function test_can_authorize(): void
    {
        $user = User::factory()->create([
            'phone' => '+375222342525',
        ]);

        (new AuthenticateUserByCredentialsAction(
            new FormatNumberFromIMaskAction(),
            new AuthenticateUserAction(),
        ))([
            'phone' => '+375 22 2342525',
            'password' => 'password'
        ]);

        $this->assertEquals(1, PersonalAccessToken::query()->count());
        tap(PersonalAccessToken::query()->first(), function (PersonalAccessToken $personalAccessToken) use ($user) {
            $this->assertEquals($user->id, $personalAccessToken->tokenable_id);
        });
    }
}
