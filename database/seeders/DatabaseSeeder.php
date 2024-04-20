<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PersonnelCategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PersonnelCategorySeeder::class,
//            CourseSeeder::class,
//            CoursePersonnelCategorySeeder::class,
//            ExamSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
//            PersonnelSeeder::class,
            AdminSeeder::class,
            PaymentCategorySeeder::class,
            PaymentFeeSeeder::class
        ]);
    }
}
