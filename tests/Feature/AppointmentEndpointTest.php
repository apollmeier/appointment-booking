<?php

use App\Models\Appointment;
use App\Models\TimeSlot;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

describe('the appointment index endpoint', function () {
    test('responds with status 200', function () {
        get(route('appointments.index'))
            ->assertOk();
    });

    test('returns appointments in the expected JSON:API structure', function () {
        Appointment::factory()->create();

        getJson(route('appointments.index'))
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => ['patientName', 'patientEmail', 'dateTime', 'status'],
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

    test('lists all appointments', function () {
        Appointment::factory()->count(5)->create();

        getJson(route('appointments.index'))
            ->assertJsonCount(5, 'data');
    });

    test('creates a new appointment when a timeslot is available and timeslot is unavailable afterwards', function () {
        $timeSlot = TimeSlot::factory()->create(['is_available' => true]);

        $payload = [
            'attributes' => [
                'patientName' => fake()->name(),
                'patientEmail' => fake()->unique()->safeEmail(),
                'dateTime' => $timeSlot->start_time->toDateTimeString(),
            ],
            'relationships' => [
                'doctor' => [
                    'data' => [
                        'type' => 'doctors',
                        'id' => $timeSlot->doctor_id,
                    ]
                ]
            ]
        ];

        postJson(route('appointments.store'), $payload)
            ->assertCreated();

        $timeSlotAfterwards = TimeSlot::find($timeSlot->id);

        expect($timeSlotAfterwards->is_available)->toBeFalse();
    });

    test('fails to create an appointment if the timeslot could not be found', function () {
        $timeSlot = TimeSlot::factory()->create(['is_available' => false]);

        $payload = [
            'attributes' => [
                'patientName' => fake()->name(),
                'patientEmail' => fake()->unique()->safeEmail(),
                'dateTime' => $timeSlot->start_time->addDay()->toDateTimeString(),
            ],
            'relationships' => [
                'doctor' => [
                    'data' => [
                        'type' => 'doctors',
                        'id' => $timeSlot->doctor_id,
                    ]
                ]
            ]
        ];

        postJson(route('appointments.store'), $payload)
            ->assertNotFound();
    });

    test('fails to create an appointment if the timeslot is unavailable', function () {
        $timeSlot = TimeSlot::factory()->create(['is_available' => false]);

        $payload = [
            'attributes' => [
                'patientName' => fake()->name(),
                'patientEmail' => fake()->unique()->safeEmail(),
                'dateTime' => $timeSlot->start_time->toDateTimeString(),
            ],
            'relationships' => [
                'doctor' => [
                    'data' => [
                        'type' => 'doctors',
                        'id' => $timeSlot->doctor_id,
                    ]
                ]
            ]
        ];

        postJson(route('appointments.store'), $payload)
            ->assertConflict();
    });

    test('cancels an appointment and the timeslot is available again afterward', function () {
        $timeSlot = TimeSlot::factory()->create(['is_available' => true]);

        $payload = [
            'attributes' => [
                'patientName' => fake()->name(),
                'patientEmail' => fake()->unique()->safeEmail(),
                'dateTime' => $timeSlot->start_time->toDateTimeString(),
            ],
            'relationships' => [
                'doctor' => [
                    'data' => [
                        'type' => 'doctors',
                        'id' => $timeSlot->doctor_id,
                    ]
                ]
            ]
        ];

        $appointment = postJson(route('appointments.store'), $payload)->json('data');

        deleteJson(route('appointments.destroy', $appointment['id']))
            ->assertOk();

        $timeSlotAfterwards = TimeSlot::find($timeSlot->id);

        expect($timeSlotAfterwards->is_available)->toBeTrue();
    });
});

describe('the appointment show endpoint', function () {
    test('responds with status 200', function () {
        $appointment = Appointment::factory()->create();

        getJson(route('appointments.show', $appointment->id))
            ->assertOk();
    });

    test('responds with 404 when the appointment does not exist', function () {
        get(route('appointments.show', 9999))
            ->assertNotFound();
    });

    test('returns a single appointment in the expected JSON:API structure', function () {
        $appointment = Appointment::factory()->create();

        getJson(route('appointments.show', $appointment->id))
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => ['patientName', 'patientEmail', 'dateTime', 'status'],
                    'relationships' => [
                        'doctor' => ['type', 'id'],
                    ],
                    'includes',
                    'links' => ['self'],
                ]
            ]);
    });
});
