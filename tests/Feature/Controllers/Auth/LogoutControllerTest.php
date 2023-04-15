<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cant_logout()
    {
        $response = $this->get(route('logout'));
        $response->assertRedirectToRoute('login');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $response = $this->get(route('logout'));
        $response->assertRedirectToRoute('login');
        $this->assertGuest();
    }
}
