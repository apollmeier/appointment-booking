<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $specializationSequence = [
            ['name' => 'Kardiologie'],
            ['name' => 'Neurologie'],
            ['name' => 'Orthopädie'],
            ['name' => 'Psychiatrie'],
            ['name' => 'Dermatologie'],
        ];

        $specializations = Specialization::factory()
            ->count(count($specializationSequence))
            ->sequence(...$specializationSequence)
            ->create();

        $doctors = Doctor::factory()
            ->count(3)
            ->recycle($specializations)
            ->create();

        $timeSlots = collect();
        foreach ($doctors as $doctor) {
            for ($dayOffset = 0; $dayOffset < 2; $dayOffset++) {
                $date = Carbon::today()->addDays($dayOffset);

                for ($hour = 8; $hour < 17; $hour++) {
                    if ($hour == 12) {
                        continue;
                    }

                    $startTime = $date->copy()->setTime($hour, '0');
                    $endTime = $startTime->copy()->addHour();

                    $timeSlots->push(TimeSlot::factory()->create([
                        'doctor_id' => $doctor->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'is_available' => true,
                    ]));
                }
            }
        }
    }
}
