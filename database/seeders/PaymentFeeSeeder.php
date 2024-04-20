<?php

namespace Database\Seeders;

use App\Models\PaymentFee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payment_fees = [
            [
                'payment_category_id' => 1,
                'personnel_category_id' => 1,
                'amount' => 50000,
            ],
            [
                'payment_category_id' => 1,
                'personnel_category_id' => 2,
                'amount' => 20000,
            ],
            [
                'payment_category_id' => 1,
                'personnel_category_id' => 3,
                'amount' => 30000,
            ],
            [
                'payment_category_id' => 2,
                'personnel_category_id' => 1,
                'amount' => 25000,
            ],
            [
                'payment_category_id' => 2,
                'personnel_category_id' => 2,
                'amount' => 8000,
            ],
            [
                'payment_category_id' => 2,
                'personnel_category_id' => 3,
                'amount' => 10000,
            ],
            [
                'payment_category_id' => 3,
                'personnel_category_id' => 1,
                'amount' => 30000,
            ],
            [
                'payment_category_id' => 3,
                'personnel_category_id' => 2,
                'amount' => 15000,
            ],
            [
                'payment_category_id' => 3,
                'personnel_category_id' => 3,
                'amount' => 20000,
            ],
        ];

        foreach ($payment_fees as $payment_fee) {
            PaymentFee::create($payment_fee);
        }
    }
}
