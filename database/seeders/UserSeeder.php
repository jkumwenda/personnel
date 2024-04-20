<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ExamNumber;
use App\Models\License;
use App\Models\PersonnelCategory;
use App\Models\RegisteredPersonnel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 users
        User::factory(10)->create()->each(function ($user) {
            $personnel_category_id = PersonnelCategory::all()->random()->id;
            $user->personnel_category_id = $personnel_category_id;
            $user->assignRole('personnel');
            $user->save();
            $user->application()->saveMany(Application::factory(1)->state(['personnel_category_id' => $personnel_category_id])->make());
            $user->registeredPersonnel()->save(RegisteredPersonnel::factory()->state(['user_id' => $user->id])->make());
            // Create ExamNumber for each user
            $user->examNumber()->save(ExamNumber::factory()->state(['user_id' => $user->id, 'personnel_category_id' => $personnel_category_id])->make());
        });
    }
}
