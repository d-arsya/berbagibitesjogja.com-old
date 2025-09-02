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

Route::match(['get', 'post'], 'from-fonnte', [BotController::class, 'fromFonnte'])->withoutMiddleware(VerifyCsrfToken::class);
Route::view('print', 'print');

Route::fallback(function () {
    return view('pages.coming');
});
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get('/auth/google/callback', [VolunteerController::class, 'authenticate']);
Route::post('/abcence/distance', [PrecenceController::class, 'userAttendance']);
Route::get('/', [VolunteerController::class, 'home'])->name('volunteer.home');
Route::redirect('/home', '/');

Route::controller(VolunteerController::class)->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'authenticate')->name('volunteer.authenticate');
    Route::post('volunteer/send-mails', 'send')->name('volunteer.send')->middleware('auth');
});
Route::middleware('auth')->group(function () {
    Route::get('volunteer/precence/qr', [PrecenceController::class, 'getQrCode'])->name('precence.qr');
    Route::resource('volunteer/precence', PrecenceController::class);
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
    Route::controller(ContributorController::class)->group(function () {
        Route::get('kontribusi/food-surplus', 'foodCreate')->name('payment.foodCreate');
        Route::post('kontribusi/food-surplus', 'foodStore')->name('payment.foodStore');
        Route::get('contributor/foods', 'food')->name('contributor.food');
        Route::delete('contributor/foods/{booking}', 'foodCancel')->name('contributor.food.destroy');
        Route::put('contributor/foods/{booking}', 'foodDone')->name('contributor.food.update');
    });
    Route::controller(LogController::class)->group(function () {
        Route::get('logs/system')->name('logs.system');
        Route::get('logs/activity', 'activityLogs')->name('logs.activity');
    });
    Route::controller(AvailabilityController::class)->name('availability.')->group(function () {
        Route::get('availability', 'dashboard')->name('dashboard');
        Route::get('availability/{time}', 'time')->name('time');
    });
    Route::get('availability/{codes}/{status}', [AvailabilityController::class, 'update']);
});
Route::middleware('guest')->group(function () {
    Route::get('/form', [HeroController::class, 'create'])->name('form.create');
    Route::post('/form', [HeroController::class, 'store'])->name('hero.store');
    Route::controller(NotifyController::class)->group(function () {
        Route::get('notify', 'form')->name('notify.form');
    });
});
Route::resource('hero', HeroController::class)->except(['show', 'edit', 'create', 'store']);
Route::get('/hero/cancel', [HeroController::class, 'cancel'])->name('hero.cancel');
Route::get('donation/food-rescue', [DonationController::class, 'rescue'])->name('donation.rescue');
Route::get('donation/charity', [DonationController::class, 'charity'])->name('donation.charity');
Route::resource('donation', DonationController::class);
Route::resource('reimburse', ReimburseController::class);
Route::get('logout', [VolunteerController::class, 'logout'])->name('logout');
