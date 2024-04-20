<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\PersonnelCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'training' => $this->faker->sentence,
            'present_employer' => $this->faker->company,
            'present_employer_address' => $this->faker->address,
            'academic_qualification' => [$this->faker->sentence],
            'professional_qualification' => [$this->faker->sentence],
            'relevant_files' => [$this->faker->fileExtension],
            'application_status' => $this->faker->randomElement(['approved']),
            'application_id' => $this->faker->unique()->randomNumber(),
        ];
    }
}
