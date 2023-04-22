<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register(): void
    {
        $this->seed();
        
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'access_token',
            'user',
        ]);
        $response->assertJson([
            'user' => [
                'first_name' => 'Tester',
                'last_name' => 'Testerov',
                'phone' => '+375222342525',
                'is_admin' => false,
            ],
        ]);

        $this->assertEquals(1, User::query()->count());
        tap(User::query()->first(), function (User $user) {
            $this->assertEquals('+375222342525', $user->phone);
            $this->assertEquals('Tester', $user->first_name);
            $this->assertEquals('Testerov', $user->last_name);
            $this->assertTrue(Hash::check('password', $user->password));
        });
    }

    public function test_phone_field_required_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
//            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('phone');
    }

    public function test_phone_field_must_be_in_right_format_to_register()
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => 'wrong',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('phone');
    }

    public function test_phone_field_must_be_unique_to_register()
    {
        User::factory([
            'phone' => '+375222342525',
        ])->create();

        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('phone');
    }

    public function test_first_name_field_required_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
//            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('first_name');
    }

    public function test_first_name_must_be_min_2_length_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'T',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('first_name');
    }

    public function test_first_name_must_be_max_30_length_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => Str::random(31),
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('first_name');
    }

    public function test_first_name_must_be_alpha_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => ' a353535',
            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('first_name');
    }

    public function test_last_name_field_required_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
//            'last_name' => 'Testerov',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('last_name');
    }

    public function test_last_name_must_be_min_2_length_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'T',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('last_name');
    }

    public function test_last_name_must_be_max_30_length_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => Str::random(31),
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('last_name');
    }

    public function test_last_name_must_be_alpha_to_register(): void
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'a 45345345',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('last_name');
    }

    public function test_password_field_required_to_register()
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
//            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertJsonValidationErrorFor('password');
    }

    public function test_password_field_must_be_min_6_length_to_register()
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'fives',
            'password_confirmation' => 'fives',
        ]);

        $response->assertJsonValidationErrorFor('password');
    }

    public function test_password_field_must_be_confirmed_to_register()
    {
        $response = $this->json(Request::METHOD_POST, route('register'), [
            'phone' => '+375 22 2342525',
            'first_name' => 'Tester',
            'last_name' => 'Testerov',
            'password' => 'sixsix',
            'password_confirmation' => 'sixsix2',
        ]);

        $response->assertJsonValidationErrorFor('password');
    }
}
