<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'personnel_category_id',
        'training',
        'present_employer',
        'present_employer_address',
        'academic_qualification',
        'professional_qualification',
        'relevant_files',
        'application_status',
        'application_id',
    ];

    protected $casts = [
        'academic_qualification' => 'array',
        'professional_qualification' => 'array',
        'relevant_files' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function personnelCategory(): BelongsTo
    {
        return $this->belongsTo(PersonnelCategory::class);
    }
}
