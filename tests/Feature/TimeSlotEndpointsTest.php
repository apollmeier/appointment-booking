<?php

use App\Models\TimeSlot;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

describe('the timeslot index endpoint', function () {
    test('responds with status 200', function () {
        get(route('timeSlots.index'))
            ->assertOk();
    });

    test('returns timeslots in the expected JSON:API structure', function () {
        TimeSlot::factory()->state([
            'is_available' => true,
        ])->create();

        getJson(route('timeSlots.index'))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => ['startTime', 'endTime', 'isAvailable'],
                        'relationships' => [
                            'doctor' => ['type', 'id'],
                        ],
                        'includes',
                        'links' => ['self'],
                    ],
                ],
                'links',
                'meta',
            ]);
    });

    test('filters out unavailable timeslots', function () {
        TimeSlot::factory()
            ->count(10)
            ->sequence(
                ['is_available' => true],
                ['is_available' => false],
            )
            ->create();

        $response = getJson(route('timeSlots.index'))
            ->assertJsonCount(5, 'data');

        collect($response['data'])->each(
            fn ($timeSlot) => expect($timeSlot['attributes']['isAvailable'])->toBeTrue()
        );
    });
});

describe('the timeslot show endpoint', function () {
    test('responds with status 200 when the timeslot exists and is available', function () {
        $timeSlot = TimeSlot::factory()->create(['is_available' => true]);

        get(route('timeSlots.show', $timeSlot))
            ->assertOk();
    });

    test('responds with 404 when the timeslot does not exist', function () {
        get(route('timeSlots.show', 9999))
            ->assertNotFound();
    });

    test('responds with 404 when the timeslot is unavailable', function () {
        $timeSlot = TimeSlot::factory()->create([
            'is_available' => false,
        ]);

        get(route('timeSlots.show', $timeSlot->id))
            ->assertNotFound();
    });

    test('returns a single timeslot in the expected JSON:API structure', function () {
        $timeSlot = TimeSlot::factory()->create([
            'is_available' => true,
        ]);

        getJson(route('timeSlots.show', $timeSlot->id))
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => ['startTime', 'endTime', 'isAvailable'],
                    'relationships' => [
                        'doctor' => ['type', 'id'],
                    ],
                    'includes',
                    'links' => ['self'],
                ],
            ]);
    });
});
