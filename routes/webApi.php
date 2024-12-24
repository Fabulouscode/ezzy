<?php

use App\Http\Controllers\WebApi\DoctorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web_decrypt_req'])->group(function () {
    Route::get('get-doctor', [DoctorController::class, "getRandomDoctor"]);
    Route::post('get-all-doctor', [DoctorController::class, "getAllDoctor"]);
});
