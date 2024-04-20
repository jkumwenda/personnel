<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'northern_region',
        'central_region',
        'southern_region',
    ];
}
