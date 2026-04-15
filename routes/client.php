<?php

use App\Http\Controllers\Client\DoctorController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/services', [ServiceController::class, 'index']);
