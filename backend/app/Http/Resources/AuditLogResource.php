<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'action' => $this->action->value,
            'model' => $this->model,
            'model_id' => $this->model_id,
            'details' => $this->details,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
