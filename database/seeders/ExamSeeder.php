<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a fake exam name

        $exam = [
            'exam_name' => 'Pharmacy Personnel Exam 2024 - 2025',
            'is_open' => true,
        ];

        Exam::create($exam);
    }
}
