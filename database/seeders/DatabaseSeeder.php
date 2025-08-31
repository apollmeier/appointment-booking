<?php

namespace Database\Seeders;

use App\Models\Specialization;
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
    }
}
