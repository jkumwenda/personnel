<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Pharmacy Law and Ethics',
            ],
            [
                'name' => 'Pharmacology  ',
            ],
            [
                'name' => 'Pharmacy Practice',
            ],
            [
                'name' => 'Clinical Pharmacy',
            ],
            [
                'name' => 'Pharmaceutics and Pharmaceutical Calculation',
            ],
            [
                'name' => 'Medicines and Allied Substances',
            ],
            [
                'name' => 'Pharmacology and Pharmacy Practice',
            ],
            [
                'name' => 'Oral Exams',
            ],
        ];
        foreach ($courses as $course) {
            Course::create($course);
        }

    }
}
