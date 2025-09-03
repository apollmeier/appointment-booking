<?php

namespace Database\Factories;

use App\Enums\AppointmentStatus;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'patient_name' => $this->faker->name(),
            'patient_email' => $this->faker->unique()->safeEmail(),
            'date_time' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement([AppointmentStatus::BOOKED, AppointmentStatus::CANCELLED]),
        ];
    }
}
