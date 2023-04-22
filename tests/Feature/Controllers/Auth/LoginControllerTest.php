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
        $this->seed();
        
        $user = User::factory()->create([
            'phone' => '+375222342525',
        ]);

        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => '+375 22 2342525',
            'password' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'access_token',
            'user',
        ]);
        $response->assertJson([
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'is_admin' => false,
                'created_at' => $user->created_at->toIsoString(),
            ],
        ]);
    }

    public function test_phone_field_required_to_authorize()
    {
        $response = $this->json(Request::METHOD_POST, route('login'), [
            'password' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('phone');
    }

    public function test_phone_field_must_be_in_right_format_to_authorize()
    {
        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => 'wrong format',
        ]);

        $response->assertJsonValidationErrorFor('phone');
    }

    public function test_password_field_required_to_authorize()
    {
        $response = $this->json(Request::METHOD_POST, route('login'), [
            'phone' => '+375 22 2342525',
        ]);

        $response->assertJsonValidationErrorFor('password');
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

        $response->assertJsonValidationErrorFor('phone');
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

        $response->assertJsonValidationErrorFor('phone');
    }
}
