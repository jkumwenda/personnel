<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenceConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'INS',
        'DG_NAME',
        'BC_NAME',
        'DG_SIGNATURE',
        'BC_SIGNATURE'
    ];
}
