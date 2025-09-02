<?php

namespace Database\Factories;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeSlot>
 */
class TimeSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = Carbon::now()->addDays(rand(0, 7))->addHours(rand(8, 16))->setMinutes(0);
        $endTime = $startTime->copy()->addHour();

        return [
            'doctor_id' => Doctor::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_available' => $this->faker->boolean(),
        ];
    }

    /**
     * Create daily time slots from 9 AM to 5 PM (8 hourly slots).
     *
     * @param Carbon $date The date for the time slots (defaults to today)
     * @return $this
     */
    public function forDaySchedule(Carbon $date): static
    {
        $timeSlots = [];

        for ($hour = 9; $hour < 17; $hour++) {
            $startTime = $date->copy()->setTime($hour, 0);
            $endTime = $startTime->copy()->addHour();

            $timeSlots[] = [
                'start_time' => $startTime,
                'end_time' => $endTime,
            ];
        }

        return $this->sequence(...$timeSlots)->count(count($timeSlots));
    }
}
