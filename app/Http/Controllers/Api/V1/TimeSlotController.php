<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\TimeSlotResource;
use App\Models\TimeSlot;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TimeSlotResource::collection(TimeSlot::available()->paginate());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return TimeSlotResource::make(TimeSlot::available()->findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return $this->error([
                'TimeSlot not found.'
            ], 404);
        }
    }
}
