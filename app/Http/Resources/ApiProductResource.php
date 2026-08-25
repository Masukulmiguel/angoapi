<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'endpoint_base' => $this->endpoint_base,
            'version' => $this->version,
            'is_active' => $this->is_active,
            'environment' => $this->environment,
        ];
    }
}
