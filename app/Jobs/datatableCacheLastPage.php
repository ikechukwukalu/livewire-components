<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Interfaces\UserDatatableQueryPagination;

class datatableCacheLastPage implements ShouldQueue, UserDatatableQueryPagination
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $cache = null;
    private $last_page = 1;
    private $simplePaginate = true;
    private $params = [];

    private $cache_time;
    private $order_by;
    private $search = null;
    private $fetch = 5;
    private $column = null;
    private $order = null;
    private $sort = null;
    private $white_list = [];

    public $page = null;

    public function __construct(string $cache, int $last_page, bool $simplePaginate, array $order_by, int $cache_time, array $white_list)
    {
        //
        $this->cache = $cache;
        $this->last_page = $last_page;
        $this->simplePaginate = $simplePaginate;
        $this->order_by = $order_by;
        $this->cache_time = $cache_time;
        $this->white_list = $white_list;

        /*
         * fetch -> [1]
         * search -> [2]
         * column -> [3]
         * order -> [4]
         * sort -> [5]
         * page -> [6]
         */

        $this->params = explode('.', $cache);

        $this->fetch = $this->params[1];
        $this->search = $this->params[2];
        $this->column = $this->params[3];
        $this->order = $this->params[4];
        $this->sort = $this->params[5];
        $this->page = $last_page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::disableQueryLog();
        $start = microtime(true);

        $this->params[6] = $this->last_page;
        $this->page = $this->last_page;
        $this->cache = implode('.', $this->params);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Cache key: " . $this->cache . " \n";

        if ($this->simplePaginate)
            $this->implement_simple_paginator();
        else
            $this->implement_numbered_paginator();


        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Paginator: " . "implement_simple_paginator" . " \n";
    }
    
    public function query_users_table()
    {
        return DB::table('users');
    }
    public function fetch_users_table()
    {
        return $this->query_users_table()->select('id', 'name', 'email', 'phone', 'gender', 'country', 'state', 'city', 'address');
    }
    public function search_query(object $query)
    {
        $query->orWhere('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('gender', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->orWhere('state', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%');
    }
    public function implement_numbered_paginator(): object
    {
        if (trim($this->search) == "") {
            return $this->no_search_numbered_paginator();
        } else {
            return $this->with_search_numbered_paginator();
        }

    }
    public function no_search_numbered_paginator(): object
    {
        if ($this->sort == "columns") {
            if (in_array($this->order_by[0], $this->white_list)) {
                return Cache::remember($this->cache, $this->cache_time, function () {
                    return $this->fetch_users_table()->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
                });
            } else {
                session()->flash('fail', 'Invalid column value!');
                return (object) [];
            }
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->orderBy('id', 'desc')->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
    public function with_search_numbered_paginator(): object
    {
        $q = trim($this->search);
        if ($this->sort == "columns") {
            if (in_array($this->order_by[0], $this->white_list)) {
                return Cache::remember($this->cache, $this->cache_time, function () {
                    return $this->fetch_users_table()->where(function ($query) {
                        $this->search_query($query);
                    })
                        ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')
                        ->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
                });
            } else {
                session()->flash('fail', 'Invalid column value!');
                return (object) [];
            }
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) {
                    $this->search_query($query);
                })
                    ->orderBy('id', 'desc')
                    ->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) {
                    $this->search_query($query);
                })
                    ->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
    public function implement_simple_paginator(): object
    {
        if (trim($this->search) == "") {
            return $this->no_search_simple_paginator();
        } else {
            return $this->with_search_simple_paginator();
        }
    }
    public function no_search_simple_paginator(): object
    {
        if ($this->sort == "columns") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->orderBy('id', 'desc')->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
    public function with_search_simple_paginator(): object
    {
        $q = trim($this->search);
        if ($this->sort == "columns") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) {
                    $this->search_query($query);
                })
                    ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')
                    ->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) {
                    $this->search_query($query);
                })
                    ->orderBy('id', 'desc')
                    ->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) {
                    $this->search_query($query);
                })
                    ->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
}
