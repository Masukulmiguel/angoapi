<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'days_until_expiration' => $this->daysUntilExpiration(),
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'created_at' => $this->created_at,
        ];
    }
}
