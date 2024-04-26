<?php

namespace App\Http\Resources;

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
            'id' => $this->id,
            'user' => $this->user,
            'name' => $this->name,
            'role_id' => $this->role_id,
            'salary' => $this->salary,
            'profit_margin' => $this->profit_margin,
            'is_active' => $this->is_active,
            'token' => $this->token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
