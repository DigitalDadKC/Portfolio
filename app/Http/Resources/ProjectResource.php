<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
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
            'description' => $this->description,
            // 'image' => asset('/storage/' . $this->image),
            'image' => $this->image ? Storage::disk('public')->url($this->image) : null,
            'skills' => SkillResource::collection($this->skills),
            'url' => $this->url,
            'order' => $this->order,
            'active' => $this->active,
            'icon' => $this->icon,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
