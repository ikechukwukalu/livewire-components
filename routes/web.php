<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\User;

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
    /**
     * Table Header | Footer columns
    */
    $columns = [
        ['name' => 'name', 'sort' => 'name'],
        ['name' => 'email', 'sort' => 'email'],
        ['name' => 'phone', 'sort' => 'phone'],
        ['name' => 'gender', 'sort' => 'gender'],
        ['name' => 'country', 'sort' => 'country'],
        ['name' => 'state', 'sort' => 'state'],
        ['name' => 'city', 'sort' => 'city'],
        ['name' => 'address', 'sort' => 'address']
    ];
    
    /**
     * ['column', 'true - asc|false - desc'] is effective if [sort] is set to columns
     * To prevent SQL injection for this example I've whitelisted the necessary columns
     * by including them into the $fillable array
    */
    $order_by = [$columns[0]['sort'], true];
    
    /**
     * Dropdown for options for number of rows that can be fetched
    */
    $page_options = [5, 10, 15, 25, 50, 100];
    
    /**
     * Default page_options
    */
    $fetch = $page_options[0];
    
    /**
     * Sort Table
     * -----------
     * columns | not recommended for large records exceeding 5k, 
     * latest | speed is very good, 
     * null | speed is the fastest
    */
    $sort = 'columns';
    
    /**
     * Max allowed for numbered paginator | switch to simple paginator
    */
    $maxP = 5000;

    return view('datatable', [
        'columns' => $columns,
        'order_by' => $order_by,
        'page_options' => $page_options,
        'fetch' => $fetch,
        'sort' => $sort, 
        'maxP' => $maxP
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