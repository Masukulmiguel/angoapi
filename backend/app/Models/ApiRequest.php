<?php

namespace App\Models;

use Database\Factories\ApiRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRequest extends Model
{
    use HasFactory;

    protected $table = 'api_requests';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'api_key_id',
        'api_product_id',
        'endpoint',
        'method',
        'status_code',
        'response_time_ms',
        'ip_address',
        'user_agent',
        'environment',
        'request_size',
        'response_size',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(ApiKey::class);
    }

    public function apiProduct(): BelongsTo
    {
        return $this->belongsTo(ApiProduct::class);
    }
}
