<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Facades\Activitylog;
use Spatie\Activitylog\Models\Activity;

class AuditService
{
    public function log(string $action, ?User $user = null, array $properties = []): ?Activity
    {
        $logger = Activitylog::log($action);

        if ($user) {
            $logger->by($user);
        }

        if (!empty($properties)) {
            $logger->withProperties($properties);
        }

        return $logger->get();
    }

    public function getForUser(User $user, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::where('causer_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecent(int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return Activity::latest()
            ->limit($limit)
            ->get();
    }
}
