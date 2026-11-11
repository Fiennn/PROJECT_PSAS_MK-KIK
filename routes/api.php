<?php

use App\Http\Controllers\Api\HomestayController;
use Illuminate\Support\Facades\Route;

Route::get('/homestay', [HomestayController::class, 'index']);
Route::get('/homestay/{homestay}', [HomestayController::class, 'show']);
