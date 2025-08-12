<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => (float) $this->value,
            'min_order_amount' => (float) $this->min_order_amount,
            'starts_at' => optional($this->starts_at)->toDateTimeString(),
            'expires_at' => optional($this->expires_at)->toDateTimeString(),
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'active' => (bool) $this->active,
            'meta' => $this->meta,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
