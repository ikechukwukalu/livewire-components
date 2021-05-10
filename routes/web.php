<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
            ['name' => 'address', 'sort' => 'address'],
        ];

        /**
         * ['column', 'true - asc|false - desc'] is effective if [sort] is set to columns
         * To prevent SQL injection for this example I've whitelisted the necessary columns
         * by including them into the $white_list array
         */
        $order_by = [$columns[0]['sort'], true];

        /**
         * Dropdown options for the number of rows that can be fetched
         */
        $page_options = [5, 10, 15, 25, 50, 100, 250, 500, 1000];

        /**
         * Default page_options value
         */
        $fetch = $page_options[0];

        /**
         * Sort Table
         * -----------
         * columns | not recommended for large records exceeding 1k,
         * latest | speed is very good,
         * null | speed is the fastest
         */
        $sort = 'columns';

        /**
         * Max allowed for numbered paginator | switch to simple paginator
         */
        $maxP = 5000;

        /**
         * Max allowed time for cached query to last
         */
        $cache_time = 300; // 5 minutes

        return view('datatable', [
            'columns' => $columns,
            'order_by' => $order_by,
            'page_options' => $page_options,
            'fetch' => $fetch,
            'sort' => $sort,
            'maxP' => $maxP,
            'cache_time' => $cache_time,
        ]);
    })->name('datatable');

    Route::get('mail', function () {
        /*
         *
         *
         * private $common_folders = [
         *      'root' => 'INBOX',
         *      'junk' => 'INBOX.Spam',
         *      'drafts' => 'INBOX.Drafts',
         *      'sent' => 'INBOX.Sent',
         *      'trash' => 'INBOX.Trash'
         * ]
         */

        /*
         * Max allowed time for cached query to last
         */
        $cache_time = 300;

        /*
         * Amount of email items to fetch
         */
        $fetch = 5; // Recommended

        /*
         * Common folders array
         */
        $common_folders = [
            'root' => 'INBOX',
            'junk' => 'INBOX.Spam',
            'drafts' => 'INBOX.Drafts',
            'sent' => 'INBOX.Sent',
            'trash' => 'INBOX.Trash',
        ];

        return view('get-mails', [
            'sender' => 'info@provirtcomm.com',
            'folder' => 'root',
            'cache_time' => $cache_time,
            'fetch' => $fetch,
            'common_folders' => $common_folders
        ]);
    })->name('get-mails');

    Route::get('infinite/scroll', function () {
        /*
         * Max allowed time for cached query to last
         */
        $cache_time = 300;

        /*
         * Amount of database items to fetch
         */
        $fetch = 15;

        return view('infinite-scroll', [
            'users' => Cache::remember('infinite-users.1', $cache_time, function () use($fetch) {
                return DB::table('users')->select('id', 'name', 'phone', 'email', 'gender')
                    ->skip(0)
                    ->take($fetch)
                    ->get();
            }),
            'cache_time' => $cache_time,
            'fetch' => $fetch
        ]);
    })->name('infinite-scroll');

    Route::prefix('sortable')->group(function () {
        Route::get('basic', function () {
            return view('sortable-basic');
        })->name('sortable-basic');

        Route::get('complex', function () {
            return view('sortable-complex');
        })->name('sortable-complex');
    });
});
