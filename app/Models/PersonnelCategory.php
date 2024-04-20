<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PersonnelCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code'
    ];


    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
    public function paymentFees()
    {
        return $this->hasMany(PaymentFee::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
