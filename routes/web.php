<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\datatableLWController;
use App\Http\Controllers\mailLWController;
use App\Http\Controllers\infiniteScrollLWController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::middleware(['throttle:50,1'])->group(function () { //Rate limiting||Prevent bruteforce and DOS attacks||Allow only 50 request per minute
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('datatable', [datatableLWController::class, 'table'])->name('datatable');
    Route::get('mail', [mailLWController::class, 'imap'])->name('get-mails');
    Route::get('infinite-scroll', [infiniteScrollLWController::class, 'scroll'])->name('infinite-scroll');

    Route::prefix('sortable')->group(function () {
        Route::get('basic', function () {
            return view('sortable-basic');
        })->name('sortable-basic');

        Route::get('complex', function () {
            return view('sortable-complex');
        })->name('sortable-complex');
    });
});
