<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_fee_id',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_receipt',
        ];

    public function paymentFee()
    {
        return $this->belongsTo(PaymentFee::class);
    }
}
