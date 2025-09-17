<?php

use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UniversityController;
use Illuminate\Support\Facades\Route;

Route::get('university/{university}/faculty', [UniversityController::class, 'getFaculties']);
Route::get('sponsor/{sponsor}/{start}/{end}', [ReportController::class, 'getDonations']);
