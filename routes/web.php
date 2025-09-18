<?php

use App\Models\Profile;
// use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Profile\TelegramController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});

Route::post('/telegram-webhook', [TelegramController::class, 'handleUpdate']);

Route::get('/getAllData', [ProfileController::class, 'index'])->middleware('auth')->name('getAllData');
Route::get('/support', [ProfileController::class, 'supports'])->middleware('auth')->name('support');
Route::get('profiles/{id}', [ProfileController::class, 'show'])->name('profiles.show');
Route::delete('/profiles/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');


Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');

// Route::get('/send-test-message', [TelegramController::class, 'sendTestMessage']);


Route::get('/test', function () {
    return view('test');
});

Route::get('/get-cities/{state_id}', [ProfileController::class, 'getCities']);
Route::get('/get-subcast/{caste_id}', [ProfileController::class, 'getSubCast']);
Route::get('/get-specific-professions/{profession_id}', [ProfileController::class, 'getSpecificProfessions']);
Route::get('/get-partner-specific-professions/{partnerProfessionId}', [ProfileController::class, 'getPartnerSpecificProfessions']);

Route::get('/terms-conditions', [ProfileController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/', [ProfileController::class, 'home'])->name('pages.home');



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


