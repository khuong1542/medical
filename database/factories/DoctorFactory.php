<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
	protected $model = Doctor::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'id' => \Str::uuid(),
			'code' => fake()->unique()->bothify('DOC###'),
			'name' => fake()->name(),
			'images' => null,
			'email' => fake()->unique()->safeEmail(),
			'phone' => fake()->phoneNumber(),
			'experience_years' => fake()->numberBetween(1, 30),
			'description' => fake()->paragraph(),
			'order' => fake()->numberBetween(1, 100),
			'status' => 1,
		];
	}
}
