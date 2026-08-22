<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'method' => $this->method,
            'status' => $this->status,
            'reference_number' => $this->reference_number,
            'payment_date' => $this->payment_date,
            'paid_by_name' => $this->paid_by_name,
            'paid_by_phone' => $this->paid_by_phone,
            'created_at' => $this->created_at,
            'proofs' => PaymentProofResource::collection($this->whenLoaded('proofs')),
        ];
    }
}
