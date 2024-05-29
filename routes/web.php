<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProxyCheckController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [ProxyCheckController::class, 'index'])->name('index');
Route::post('/check', [ProxyCheckController::class, 'check'])->name('check');

Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
Route::get('/history/{proxy_check_result}', [HistoryController::class, 'show'])->name('history.show');