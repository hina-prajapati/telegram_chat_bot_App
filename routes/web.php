<?php

use App\Models\Profile;
// use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;
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


// Route::get('/send-test-message', [TelegramController::class, 'sendTestMessage']);


Route::get('/test', [TelegramController::class, 'test'])->name('test');


