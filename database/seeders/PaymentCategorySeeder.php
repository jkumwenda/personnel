<?php

namespace Database\Seeders;

use App\Models\PaymentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_categories = [
            ['name' => 'new'],
            ['name' => 'resit'],
            ['name' => 'retention'],
        ];

        foreach ($payment_categories as $payment_category) {
            PaymentCategory::create($payment_category);
        }
    }
}
