<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_name',
        'is_open',
        'published',
        'send_for_approval',
        'dg_approved',
        'bc_approved',
        'start_date',
        'end_date',
        'published_date'
    ];

    function examNumbers()
    {
        return $this->hasMany(ExamNumber::class);
    }
}
