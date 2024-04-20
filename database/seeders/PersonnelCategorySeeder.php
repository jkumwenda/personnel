<?php

namespace Database\Seeders;

use App\Models\PersonnelCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonnelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create 3 personnel categories
        // 1. Pharmacist, 2. Pharmacy Assistant, 3. Pharmacy Technician

        $categories = [
            'Pharmacist',
            'Pharmacy Assistant',
            'Pharmacy Technologist',
        ];

        foreach ($categories as $category) {
            PersonnelCategory::create([
                'name' => $category,
            ]);
        }

    }
}
