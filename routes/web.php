<?php

use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::view('print', 'print');

Route::fallback(function () {
    return view('pages.coming');
});
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('/auth/google/callback', [VolunteerController::class, 'authenticate']);
Route::get('/', [VolunteerController::class, 'home'])->name('volunteer.home');
Route::redirect('/home', '');
Route::redirect('/form', 'login');

Route::controller(VolunteerController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('volunteer.authenticate');
});
Route::middleware('auth')->group(function () {
    Route::resource('volunteer', VolunteerController::class);
    Route::resource('food', FoodController::class)->except(['show', 'create']);
    Route::get('/sponsor/individu', [SponsorController::class, 'individu'])->name('sponsor.individu');
    Route::post('/hero/contributor', [HeroController::class, 'contributor'])->name('hero.contributor');
    Route::resource('sponsor', SponsorController::class);
    Route::get('/hero/faculty/{faculty}', [HeroController::class, 'faculty'])->name('hero.faculty');
    Route::get('/hero/backups', [HeroController::class, 'backups'])->name('hero.backups');
    Route::get('/hero/restore/{backup}', [HeroController::class, 'restore'])->name('hero.restore');
    Route::delete('/hero/trash/{backup}', [HeroController::class, 'trash'])->name('hero.trash');
    Route::resource('beneficiary', BeneficiaryController::class);
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::post('/report/download', [ReportController::class, 'download'])->name('report.download');
    Route::get('/report/clean', [ReportController::class, 'clean'])->name('report.clean');
});
Route::middleware('guest')->group(function () {
    Route::get('/form', [HeroController::class, 'create'])->name('form.create');
    Route::post('/form', [HeroController::class, 'store'])->name('hero.store');
});
Route::resource('hero', HeroController::class)->except(['show', 'edit', 'create', 'store']);
Route::get('/hero/cancel', [HeroController::class, 'cancel'])->name('hero.cancel');
Route::get('donation/food-rescue', [DonationController::class, 'rescue'])->name('donation.rescue');
Route::get('donation/charity', [DonationController::class, 'charity'])->name('donation.charity');
Route::resource('donation', DonationController::class);
Route::get('logout', [VolunteerController::class, 'logout'])->name('logout');
