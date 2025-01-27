<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UniversityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('university/{university}/faculty', [UniversityController::class, 'getFaculties']);
Route::get('sponsor/{sponsor}/{start}/{end}', [ReportController::class, 'getDonations']);
