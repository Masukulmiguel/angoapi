<?php

namespace App\Models;

use Database\Factories\ApiKeyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'subscription_id',
        'name',
        'key_hash',
        'key_prefix',
        'abilities',
        'is_active',
        'last_used_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'abilities' => 'array',
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function apiRequests(): HasMany
    {
        return $this->hasMany(ApiRequest::class);
    }

    public static function generateForUser($user): array
    {
        $plainText = 'ango_' . Str::random(48);
        $hash = hash('sha256', $plainText);
        $prefix = substr($plainText, 0, 9);

        return [
            'key' => $plainText,
            'key_hash' => $hash,
            'key_prefix' => $prefix,
        ];
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function recordUsage(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
