<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_authenticate(): void
    {
        $user = User::factory()->create([
            'phone' => '+375222342525',
        ]);

        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => '+375 22 2342525',
            'password' => 'password',
        ]);

        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_phone_field_required_to_authorize()
    {
        $response = $this->json(Request::METHOD_POST, route('login'), [
            'password' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('phone');
        $this->assertGuest();
    }

    public function test_phone_field_must_be_in_right_format_to_authorize()
    {
        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => 'wrong format',
        ]);

        $response->assertJsonValidationErrorFor('phone');
        $this->assertGuest();
    }

    public function test_password_field_required_to_authorize()
    {
        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => '+375 22 2342525',
        ]);

        $response->assertJsonValidationErrorFor('password');
        $this->assertGuest();
    }

    public function test_cant_authorize_with_wrong_phone()
    {
        User::factory()->create([
            'phone' => '+375222342525',
        ]);

        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => '+375 22 3341525',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrorsIn('phone');
        $this->assertGuest();
    }

    public function test_cant_authorize_with_wrong_password()
    {
        User::factory()->create([
            'phone' => '+375 22 2342525',
        ]);

        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => '+375 22 2342525',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrorsIn('phone');
        $this->assertGuest();
    }
}
