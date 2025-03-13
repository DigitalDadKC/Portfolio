<?php

namespace App\Http\Resources;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
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
            'number' => $this->number,
            'address' => $this->address,
            'city' => $this->city,
            'state' => StateResource::make($this->state),
            'total' => $this->total,
            'formatted_total' => $this->formatted_total,
            'days' => $this->days,
            'start_date' => $this->start_date,
            'notes' => $this->notes,
            'scopes' => ScopeResource::collection($this->scopes),
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
