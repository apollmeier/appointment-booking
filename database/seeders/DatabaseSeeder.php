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
            ->count(2)
            ->recycle($specializations)
            ->create();

        foreach ($doctors as $doctor) {
            TimeSlot::factory()
                ->forDaySchedule(Carbon::tomorrow())
                ->state([
                    'doctor_id' => $doctor->id,
                    'is_available' => true,
                ])
                ->create();
        }
    }
}
