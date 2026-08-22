<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price_monthly' => $this->price_monthly,
            'price_annual' => $this->price_annual,
            'request_limit_monthly' => $this->request_limit_monthly,
            'features' => $this->features,
            'is_active' => $this->is_active,
            'api_products' => ApiProductResource::collection($this->whenLoaded('api_products')),
        ];
    }
}
