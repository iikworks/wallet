<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\AuthenticateUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticateUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticate(): void
    {
        $user = User::factory()->create();
        (new AuthenticateUserAction)($user);

        $this->assertAuthenticatedAs($user);
    }
}
