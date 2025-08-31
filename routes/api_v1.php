<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\V1\TimeSlotController;
use Illuminate\Support\Facades\Route;


Route::apiResource('doctors', DoctorController::class)->only(['index', 'show']);
Route::apiResource('timeSlots', TimeSlotController::class)->except(['store', 'destroy']);
Route::apiResource('appointments', AppointmentController::class);
