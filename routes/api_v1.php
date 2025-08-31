<?php

use App\Http\Controllers\Api\V1\DoctorController;
use Illuminate\Support\Facades\Route;


Route::apiResource('doctors', DoctorController::class)->only(['index', 'show']);
