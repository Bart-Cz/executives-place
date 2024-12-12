<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'currency' => $this->currency,
            'hourly_rate' => number_format($this->hourly_rate, 2),
            // created_at can be useful to know when user created an account, updated_at less so
            'created_at' => $this->created_at,
        ];
    }
}
