<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_number',
        'user_id',
        'exam_id',
        'personnel_category_id',
        'sequential_number'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function exam()
    {
        return $this->belongsTo(Exam::class);
    }

}
