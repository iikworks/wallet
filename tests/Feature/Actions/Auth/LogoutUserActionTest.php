<?php

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\LogoutUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        (new LogoutUserAction)();

        $this->assertGuest();
    }
}
