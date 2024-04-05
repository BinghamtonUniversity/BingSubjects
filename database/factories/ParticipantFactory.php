<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
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
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'sex' => $this->faker->randomElement(['male','female','intersex']),
            'gender' => $this->faker->randomElement(['man','woman','non_binary']),
            'race' => $this->faker->randomElement(['american_native','asian','black','pacific_islander','white']),
            'ethnicity' => $this->faker->randomElement(['hispanic','not_hispanic']),
            'city_of_birth' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->numerify('###-###-####'),
        ];
    }
}
