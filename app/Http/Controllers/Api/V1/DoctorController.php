<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DoctorResource;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\ApiResponses;

class DoctorController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DoctorResource::collection(Doctor::paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return DoctorResource::make(Doctor::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'title' => 'Doctor not found',
                'detail' => "Could not find doctor with ID $id",
            ], 404);
        }
    }
}
