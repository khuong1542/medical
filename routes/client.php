<?php

use App\Http\Controllers\Client\DoctorController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ServiceController;
use Illuminate\Support\Facades\Route;

Route::name('client.')->group(function () {
	Route::get('/', [HomeController::class, 'index']);
	Route::prefix('doctors')->name('doctors.')->group(function () {
		Route::get('', [DoctorController::class, 'index'])->name('index');
		Route::get('detail/{code}', [DoctorController::class, 'detail'])->name('detail');
		Route::get('booking/{code}', [DoctorController::class, 'booking'])->name('booking');
	});
	Route::get('/services', [ServiceController::class, 'index']);
});
