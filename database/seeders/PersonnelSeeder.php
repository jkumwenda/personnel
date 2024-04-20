<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonnelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Personnel User',
            'email' => 'personnel@user.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole('personnel');
    }
}
