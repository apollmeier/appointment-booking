<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'specializations',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'createdAt' => $this->created_at,
                'updateAt' => $this->updated_at,
            ]
        ];
    }
}
