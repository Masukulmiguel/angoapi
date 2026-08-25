<?php

namespace App\Models;

use Database\Factories\ApiProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'endpoint_base',
        'version',
        'is_active',
        'requires_subscription',
        'environment',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'requires_subscription' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_api_product');
    }

    public function apiRequests(): HasMany
    {
        return $this->hasMany(ApiRequest::class);
    }
}
