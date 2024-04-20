<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_number',
        'effective_date',
        'expiry_date',
        'is_revoked',
        'revoke_reason',
        'qr_code',
        'data_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
