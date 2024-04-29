<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->lastName(),
            'company' => fake()->company(),
            'phone_number' => fake()->phoneNumber(),
            'mobile_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->date('Y-m-d', 'now'),
            'deleted_at' => null,
        ];
    }
}
