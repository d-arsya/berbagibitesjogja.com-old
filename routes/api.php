<?php

use App\Http\Controllers\HeroController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('university/{university}/faculty', [HeroController::class, 'getFaculties']);
Route::get('sponsor/{sponsor}/{start}/{end}', [ReportController::class, 'getDonations']);
