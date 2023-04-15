<?php

namespace Tests\Feature\Actions\Users;

use App\Actions\Auth\AuthenticateUserAction;
use App\Actions\Users\FormatNumberFromIMaskAction;
use App\Actions\Users\StoreUserAction;
use App\Actions\Users\StoreUserAndAuthenticateAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Tests\TestCase;

class StoreUserAndAuthenticateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_number_must_be_unique(): void
    {
        $this->expectException(UnprocessableEntityHttpException::class);

        User::factory()->create([
            'phone' => '+375232445643'
        ]);

        (new StoreUserAndAuthenticateAction(
            new StoreUserAction(
                new FormatNumberFromIMaskAction(),
            ),
            new AuthenticateUserAction(),
        ))([
            'phone' => '+375 23 2445643',
        ]);
    }

    public function test_can_store()
    {
        (new StoreUserAndAuthenticateAction(
            new StoreUserAction(
                new FormatNumberFromIMaskAction(),
            ),
            new AuthenticateUserAction(),
        ))([
            'phone' => '+375 23 2445643',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'password',
        ]);

        $this->assertEquals(1, User::query()->count());
        tap(User::query()->first(), function (User $user) {
            $this->assertEquals('+375232445643', $user->phone);
            $this->assertEquals('Tester', $user->first_name);
            $this->assertEquals('Testerov', $user->last_name);
            $this->assertTrue(Hash::check('password', $user->password));
        });
    }
}
