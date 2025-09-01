<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;
use App\Models\TimeSlot;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        $requestData = $request->all();

        try {
            $timeSlot = TimeSlot::where('doctor_id', $requestData['relationships']['doctor']['data']['id'])->where('start_time', $requestData['attributes']['dateTime'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'title' => 'Time slot not found',
                'detail' => "Could not find available time slot with the given date time",
            ], 404);
        }

        if (!$timeSlot->is_available) {
            return $this->error([
                'title' => 'Time slot is not available',
                'detail' => "The selected time slot is not available",
            ], 404);
        }

        $timeSlot->update(['is_available' => false]);

        $appointment = Appointment::create([
            'doctor_id' => $timeSlot->doctor_id,
            'patient_name' => $requestData['attributes']['patientName'],
            'patient_email' => $requestData['attributes']['patientEmail'],
            'date_time' => $timeSlot->start_time,
            'status' => 'booked',
        ]);

        return AppointmentResource::make($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
