<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EmployeeFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = Employee::class;

    public function definition()
    {
        return [
            'team_id' => Team::inRandomOrder()->value('id') ?? Team::factory(),
            'email' => $this->faker->unique()->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'password' => 'password',
            'gender' => $this->faker->randomElement(['1', '2']),
            'birthday' => $this->faker->date(),
            'address' => $this->faker->address,
            'avatar' => $this->faker->imageUrl(128, 128),
            'salary' => $this->faker->numberBetween(3000, 10000),
            'position' => $this->faker->randomElement(['1', '2', '3', '4', '5']),
            'status' => $this->faker->randomElement(['1', '2']),
            'type_of_work' => $this->faker->randomElement(['1', '2', '3', '4']),
            'ins_id' => 1,
            'upd_id' => null,
            'del_flag' => IS_NOT_DELETED,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
