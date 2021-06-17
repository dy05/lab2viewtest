<?php

namespace App\Models;

use App\Models\Concerns\MustNotifyAfterCreated;
use App\Models\Concerns\MustNotifyAfterDeleted;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use BroadcastsEvents;
    use HasFactory;
    use HasApiTokens;
    use MustNotifyAfterCreated;
    use MustNotifyAfterDeleted;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'logs',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function logs(): HasMany
    {
        return $this->hasMany(Log::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function ($user) {
            self::userCreated($user);
        });

        self::deleted(function ($user) {
            self::userDeleted($user);
        });
    }
}
