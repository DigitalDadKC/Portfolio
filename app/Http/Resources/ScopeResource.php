<?php

namespace App\Http\Resources;

use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScopeResource extends JsonResource
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
            'name' => $this->name,
            'area' => $this->area,
            'days' => $this->days,
            'total' => $this->total,
            'formatted_total' => $this->formatted_total,
            'lines' => LineResource::collection($this->lines)
        ];
    }
}
