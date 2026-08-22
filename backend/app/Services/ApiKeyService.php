<?php

namespace App\Services;

use App\Models\ApiKey;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class ApiKeyService
{
    public function generate(User $user, Subscription $subscription): array
    {
        $generated = ApiKey::generateForUser($user);

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'name' => 'API Key',
            'key_hash' => hash('sha256', $generated['key']),
            'key_prefix' => $generated['key_prefix'],
            'is_active' => true,
            'expires_at' => $subscription->end_date,
        ]);

        return [
            'key' => $generated['key'],
            'api_key' => $apiKey,
        ];
    }

    public function revoke(ApiKey $key): bool
    {
        return $key->delete();
    }

    public function regenerate(ApiKey $key): array
    {
        $this->revoke($key);

        return $this->generate(
            $key->user,
            $key->subscription
        );
    }

    public function validate(string $key): ?ApiKey
    {
        $hash = hash('sha256', $key);

        $apiKey = ApiKey::where('key_hash', $hash)
            ->where('is_active', true)
            ->first();

        if (!$apiKey) {
            return null;
        }

        if ($apiKey->isExpired()) {
            return null;
        }

        $apiKey->recordUsage();

        return $apiKey;
    }

    public function getForUser(User $user): Collection
    {
        return $user->apiKeys()
            ->with('subscription')
            ->latest()
            ->get();
    }
}
