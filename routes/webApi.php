<?php

use App\Http\Controllers\WebApi\ContactUsController;
use App\Http\Controllers\WebApi\DoctorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web_decrypt_req'])->group(function () {
    Route::get('get-doctor', [DoctorController::class, "getRandomDoctor"]);
    Route::post('get-all-doctor', [DoctorController::class, "getAllDoctor"]);
    Route::get('get-country', [ContactUsController::class, "getAllCountry"]);
    Route::post('contact-us', [ContactUsController::class, "addContactDetails"]);
});
