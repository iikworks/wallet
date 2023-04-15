<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phoneStart = '+375';
        $operatorCodes = collect([29, 25, 33, 44]);
        $phone = rand(1000000, 9999999);

        return [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'phone' => $phoneStart . $operatorCodes->random() . $phone,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
