<?php

namespace Database\Factories;

use App\Models\RegisteredPersonnel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegisteredPersonnelFactory extends Factory
{
    protected $model = RegisteredPersonnel::class;

    public function definition()
    {
        return [
            'exam_id' => 5,
            'is_registered' => true,
        ];
    }
}
