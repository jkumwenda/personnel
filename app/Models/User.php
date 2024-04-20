<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
//    implements MustVerifyEmailContract
{
    use HasApiTokens, HasFactory, Notifiable, /*MustVerifyEmail*/ HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'gender',
        'phone_number',
        'personnel_category_id',
        'postal_address',
        'physical_address',
        'country',
        'national_id',
        'national_file',
        'exam_status',
        'email',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function application(): HasOne
    {
        return $this->hasOne(Application::class);
    }

    public function personnelCategory(): BelongsTo
    {
        return $this->belongsTo(PersonnelCategory::class);
    }

    public function examNumber(): HasOne
    {
        return $this->hasOne(ExamNumber::class);
    }

    public function license(): HasOne
    {
        return $this->hasOne(License::class);
    }

    public function registeredPersonnel(): HasMany
    {
        return $this->hasMany(RegisteredPersonnel::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
