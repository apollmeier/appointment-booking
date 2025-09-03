<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'appointments',
            'id' => $this->id,
            'attributes' => [
                'patientName' => $this->patient_name,
                'patientEmail' => $this->patient_email,
                'dateTime' => $this->date_time,
                'status' => $this->status,
            ],
            'relationships' => [
                'doctor' => [
                    'type' => 'doctors',
                    'id' => $this->doctor_id,
                ],
            ],
            'includes' => [DoctorResource::make($this->doctor)],
            'links' => [
                'self' => route('appointments.show', $this->id),
            ],
        ];
    }
}
