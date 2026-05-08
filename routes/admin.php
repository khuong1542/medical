<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::prefix('users')->name('users.')->group(function () {
	Route::get('', [UserController::class, 'index'])->name('index');
});

Route::prefix('facilities')->name('facilities.')->group(function () {
	Route::get('', [FacilityController::class, 'index'])->name('index');
	Route::get('loadList', [FacilityController::class, 'loadList'])->name('loadList');
	Route::get('create', [FacilityController::class, 'create'])->name('create');
	Route::post('store', [FacilityController::class, 'store'])->name('store');
	Route::get('edit/{id}', [FacilityController::class, 'edit'])->name('edit');
	Route::put('update/{id}', [FacilityController::class, 'update'])->name('update');
	Route::put('changeStatus/{id}', [FacilityController::class, 'changeStatus'])->name('changeStatus');
	Route::post('updateOrder', [FacilityController::class, 'updateOrder'])->name('updateOrder');
	Route::delete('destroy/{id}', [FacilityController::class, 'destroy'])->name('destroy');
});

Route::prefix('specialties')->name('specialties.')->group(function () {
	Route::get('', [SpecialtyController::class, 'index'])->name('index');
	Route::get('loadList', [SpecialtyController::class, 'loadList'])->name('loadList');
	Route::get('create', [SpecialtyController::class, 'create'])->name('create');
	Route::post('store', [SpecialtyController::class, 'store'])->name('store');
	Route::get('edit/{id}', [SpecialtyController::class, 'edit'])->name('edit');
	Route::put('update/{id}', [SpecialtyController::class, 'update'])->name('update');
	Route::put('changeStatus/{id}', [SpecialtyController::class, 'changeStatus'])->name('changeStatus');
	Route::post('updateOrder', [SpecialtyController::class, 'updateOrder'])->name('updateOrder');
	Route::delete('destroy/{id}', [SpecialtyController::class, 'destroy'])->name('destroy');
});

Route::prefix('doctors')->name('doctors.')->group(function () {
	Route::get('', [DoctorController::class, 'index'])->name('index');
	Route::get('loadList', [DoctorController::class, 'loadList'])->name('loadList');
	Route::get('create', [DoctorController::class, 'create'])->name('create');
	Route::post('store', [DoctorController::class, 'store'])->name('store');
	Route::get('edit/{id}', [DoctorController::class, 'edit'])->name('edit');
	Route::put('update/{id}', [DoctorController::class, 'update'])->name('update');
	Route::put('changeStatus/{id}', [DoctorController::class, 'changeStatus'])->name('changeStatus');
	Route::post('updateOrder', [DoctorController::class, 'updateOrder'])->name('updateOrder');
	Route::delete('destroy/{id}', [DoctorController::class, 'destroy'])->name('destroy');
});
