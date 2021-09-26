<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class infiniteScrollLWController extends Controller
{
    /*
     * Max allowed time for cached query to last
     */
    private $cache_time = 300;

    /*
     * Amount of database items to fetch
     */
    private $fetch = 15;

    public function scroll() {
        return view('infinite-scroll', [
            'users' => Cache::remember('infinite-users.1', $this->cache_time, function () {
                return DB::table('users')->select('id', 'name', 'phone', 'email', 'gender')
                    ->skip(0)
                    ->take($this->fetch)
                    ->get();
            }),
            'cache_time' => $this->cache_time,
            'fetch' => $this->fetch
        ]);
    }
}
