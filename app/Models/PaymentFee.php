<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_category_id',
        'personnel_category_id',
        'amount',
    ];

    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class);
    }

    public function personnelCategory()
    {
        return $this->belongsTo(PersonnelCategory::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
