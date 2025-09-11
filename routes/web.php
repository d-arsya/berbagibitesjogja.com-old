<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\BotController;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\PrecenceController;
use App\Http\Controllers\ReimburseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('monthly-report/{code}', [ReportController::class, 'downloadMonthly'])->name('downloadMonthly');
Route::match(['get', 'post'], 'from-fonnte', [BotController::class, 'fromFonnte'])->withoutMiddleware(VerifyCsrfToken::class);
Route::view('print', 'print');

Route::fallback(function () {
    return view('pages.coming');
});
Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::post('abcence/distance', [PrecenceController::class, 'userAttendance']);
Route::redirect('home', '');

Route::controller(VolunteerController::class)->group(function () {
    Route::get('', 'home')->name('volunteer.home');
    Route::get('auth/google/callback', 'authenticate');
    Route::get('login', 'login')->name('login');
    Route::get('logout', 'logout')->name('logout')->middleware('auth');
});
Route::get('donation/food-rescue', [DonationController::class, 'rescue'])->name('donation.rescue');
Route::get('donation/charity', [DonationController::class, 'charity'])->name('donation.charity');
Route::middleware('auth')->group(function () {
    Route::get('volunteer/precence/qr', [PrecenceController::class, 'getQrCode'])->name('precence.qr');
    Route::get('sponsor/individu', [SponsorController::class, 'individu'])->name('sponsor.individu');

    Route::controller(HeroController::class)->name('hero.')->group(function () {
        Route::post('hero/contributor', 'contributor')->name('contributor');
        Route::get('hero/faculty/{faculty}', 'faculty')->name('faculty');
        Route::get('hero/backups', 'backups')->name('backups');
        Route::get('hero/restore/{backup}', 'restore')->name('restore');
        Route::delete('hero/trash/{backup}', 'trash')->name('trash');
    });

    Route::controller(ReportController::class)->name('report.')->group(function () {
        Route::get('report', 'index')->name('index');
        Route::post('report/download', 'download')->name('download');
        Route::get('report/clean', 'clean')->name('clean');
    });

    Route::controller(ContributorController::class)->group(function () {
        Route::get('kontribusi/food-surplus', 'foodCreate')->name('payment.foodCreate');
        Route::post('kontribusi/food-surplus', 'foodStore')->name('payment.foodStore');
        Route::get('contributor/foods', 'food')->name('contributor.food');
        Route::delete('contributor/foods/{booking}', 'foodCancel')->name('contributor.food.destroy');
        Route::put('contributor/foods/{booking}', 'foodDone')->name('contributor.food.update');
    });
    Route::controller(LogController::class)->name('logs.')->group(function () {
        Route::get('logs/system')->name('system');
        Route::get('logs/activity', 'activityLogs')->name('activity');
    });
    Route::controller(AvailabilityController::class)->name('availability.')->group(function () {
        Route::get('availability', 'dashboard')->name('dashboard');
        Route::get('availability/{time}', 'time')->name('time');
    });
    Route::get('availability/{codes}/{status}', [AvailabilityController::class, 'update']);
    Route::resource('volunteer/precence', PrecenceController::class);
    Route::resource('volunteer', VolunteerController::class);
    Route::resource('food', FoodController::class)->except(['show', 'create']);
    Route::resource('sponsor', SponsorController::class);
    Route::resource('beneficiary', BeneficiaryController::class);
    Route::resource('donation', DonationController::class);
    Route::resource('reimburse', ReimburseController::class);
});
Route::middleware('guest')->group(function () {
    Route::get('form', [HeroController::class, 'create'])->name('form.create');
    Route::get('notify', [NotifyController::class, 'form'])->name('notify.form');
    Route::post('form', [HeroController::class, 'store'])->name('hero.store');
});
Route::get('hero/cancel', [HeroController::class, 'cancel'])->name('hero.cancel');
Route::resource('hero', HeroController::class)->except(['show', 'edit', 'create', 'store']);
