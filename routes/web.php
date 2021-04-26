<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('datatable', function () {
    return view('datatable', [
        'order_by' => ['name', 'asc'], // ['column', 'asc|desc'] is effective if [sort] is set to columns
        'page_options' => [5, 10, 15, 25, 50, 100],
        'pages_displayed' => 10, // default page_options
        'sort' => 'latest', // OrderBy - [columns] | speed is okay, [latest] | speed is very good, [null] | speed is the fastest
        'maxP' => 5000 // Max allowed for numbered paginator | switch to simple paginator
    ]);
})->name('datatable');

Route::prefix('sortable')->group(function () {
    Route::get('basic', function () {
        return view('sortable-basic');
    })->name('sortable-basic');

    Route::get('complex', function () {
        return view('sortable-complex');
    })->name('sortable-complex');
});