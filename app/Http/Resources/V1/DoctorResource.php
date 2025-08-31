<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'doctors',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'createdAt' => $this->created_at,
                'updateAt' => $this->updated_at,
            ],
            'relationships' => [
                'specialization' => [
                    'data' => [
                        'type' => 'specializations',
                        'id' => $this->specialization_id
                    ]
                ]
            ],
            'includes' => $this->when($request->routeIs('doctors.*'), SpecializationResource::make($this->specialization)),
            'links' => [
                'self' => route('doctors.show', $this->id)
            ]
        ];
    }
}
