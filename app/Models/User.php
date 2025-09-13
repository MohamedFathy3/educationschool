<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, Notifiable, LogsActivity;
    use SoftDeletes;
    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }
    public function getAvatarAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function phoneKey(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'phone_key_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'first_login_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'sale_man' => 'boolean',
            'access_all_charges' => 'boolean',
            'hide_other_records' => 'boolean',
            'role' => UserRole::class,
        ];
    }
}
