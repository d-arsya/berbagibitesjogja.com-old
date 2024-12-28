<?php

use App\Http\Controllers\DonationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::get('test-email', [VolunteerController::class, 'store']);

Route::fallback(function () {
    return view('pages.coming');
});
Route::get('/', [VolunteerController::class,'home'])->name('volunteer.home');
Route::redirect('/home', '/');


Route::controller(VolunteerController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('volunteer.authenticate');
});
Route::get('/form', [HeroController::class, 'create'])->name('form.create');
Route::post('/form', [HeroController::class, 'store'])->name('hero.store');
Route::get('/hero/cancel', [HeroController::class, 'cancel'])->name('hero.cancel');
Route::post('/hero/contributor', [HeroController::class, 'contributor'])->name('hero.contributor');
Route::resource('volunteer', VolunteerController::class);
Route::resource('donation', DonationController::class);
Route::resource('sponsor', SponsorController::class);
Route::resource('food', FoodController::class)->except(['show', 'create']);
Route::resource('hero', HeroController::class)->except(['show', 'edit', 'create', 'store']);
Route::get('/hero/faculty/{faculty}', [HeroController::class, 'faculty'])->name('hero.faculty');
Route::get('/hero/backups', [HeroController::class, 'backups'])->name('hero.backups');
Route::get('/hero/restore/{backup}', [HeroController::class, 'restore'])->name('hero.restore');
Route::delete('/hero/trash/{backup}', [HeroController::class, 'trash'])->name('hero.trash');
Route::get('logout', [VolunteerController::class, 'logout'])->name('logout');
