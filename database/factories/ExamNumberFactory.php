<?php

namespace Database\Factories;

use App\Models\ExamNumber;
use App\Models\User;
use App\Models\Exam;
use App\Models\PersonnelCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamNumberFactory extends Factory
{
    protected $model = ExamNumber::class;

    public function definition()
    {
        return [
            'exam_number' => sprintf('PMRA/%02d/%02d', $this->faker->numberBetween(1, 99), $this->faker->numberBetween(1, 99)),
            'exam_id' => 5,
            'sequential_number' => $this->faker->unique()->randomNumber(),
        ];
    }
}
