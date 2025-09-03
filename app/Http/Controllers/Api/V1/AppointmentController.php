<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAppointmentRequest;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AppointmentResource::collection(Appointment::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        try {
            $timeSlot = TimeSlot::where('doctor_id', $request->input('relationships.doctor.data.id'))->where('start_time', $request->input('attributes.dateTime'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'TimeSlot not found.',
            ], 404);
        }

        if (! $timeSlot->is_available) {
            return $this->error([
                'TimeSlot is not available.',
            ], 409);
        }

        $timeSlot->makeUnavailable();

        $appointment = Appointment::create([
            'doctor_id' => $timeSlot->doctor_id,
            'patient_name' => $request->input('attributes.patientName'),
            'patient_email' => $request->input('attributes.patientEmail'),
            'date_time' => $timeSlot->start_time,
            'status' => AppointmentStatus::BOOKED,
        ]);

        return AppointmentResource::make($appointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return AppointmentResource::make(Appointment::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'Appointment not found.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'Appointment not found.',
            ], 404);
        }

        try {
            $timeSlot = TimeSlot::where('doctor_id', $appointment->doctor_id)->where('start_time', $appointment->date_time)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'TimeSlot not found.',
            ], 404);
        }

        $timeSlot->makeAvailable();

        $appointment->cancel();

        return $this->success('Appointment cancelled.');
    }
}
