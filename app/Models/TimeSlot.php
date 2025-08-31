<?php

namespace App\Models;

use Database\Factories\TimeSlotFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSlot extends Model
{
    /** @use HasFactory<TimeSlotFactory> */
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start_time',
        'end_time',
        'is_available',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_available' => 'boolean',
    ];

    protected $with = ['doctor'];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    #[Scope]
    protected function available(Builder $query): void
    {
        $query->where('is_available', true);
    }
}
