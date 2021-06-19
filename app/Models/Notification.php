<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Notification
 * @package App\Models
 *
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'user_id', 'status'];

    protected $appends = ['users'];

    public function viewers(): HasMany
    {
        return $this->hasMany(NotificationView::class, 'notification_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUsersAttribute(): array
    {
        return $this->viewers()->pluck('user_id')->all();
    }
}
