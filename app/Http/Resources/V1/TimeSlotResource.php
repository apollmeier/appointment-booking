<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'timeSlots',
            'id' => $this->id,
            'attributes' => [
                'startTime' => $this->start_time,
                'endTime' => $this->end_time,
                'isAvailable' => $this->is_available,
            ],
            'relationships' => [
                'doctor' => [
                    'type' => 'doctors',
                    'id' => $this->doctor_id,
                ],
            ],
            'includes' => [DoctorResource::make($this->doctor)],
            'links' => [
                'self' => route('timeSlots.show', $this->id),
            ],
        ];
    }
}
