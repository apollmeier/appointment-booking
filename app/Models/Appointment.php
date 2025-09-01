<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_name',
        'patient_email',
        'date_time',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'date_time' => 'datetime',
            'status' => AppointmentStatus::class
        ];
    }

    protected $with = ['doctor'];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function cancel(): void
    {
        $this->update(['status' => AppointmentStatus::CANCELLED]);
    }
}
