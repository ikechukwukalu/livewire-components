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

class cachePage implements ShouldQueue, UserDatatableQueryPagination
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

    private $order_by;
    private $search = null;
    private $fetch = 5;
    private $column = null;
    private $order = null;
    private $sort = null;
    private $white_list = [];

    public $page = null;

    public function __construct(string $cache, int $page, bool $simplePaginate)
    {
        //
        $this->cache = $cache;
        $this->simplePaginate = $simplePaginate;

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
        
        $this->page = $page;
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
        
        if ($this->simplePaginate) {
            $time_elapsed_secs = microtime(true) - $start;
            echo "Part 1: " . $time_elapsed_secs . ", Cache key: " . $this->cache . " \n";

            $users = $this->implement_simple_paginator();

            $time_elapsed_secs = microtime(true) - $start;
            echo "Part 2: " . $time_elapsed_secs . ", Paginator: " . "implement_simple_paginator" . " \n";
        } else {
            $time_elapsed_secs = microtime(true) - $start;
            echo "Part 1: " . $time_elapsed_secs . ", Cache key: " . $this->cache . " \n";

            $users = $this->implement_numbered_paginator();

            $time_elapsed_secs = microtime(true) - $start;
            echo "Part 2: " . $time_elapsed_secs . ", Paginator: " . "implement_numbered_paginator" . " \n";
        }
    }
    
    public function query_users_table()
    {
        return DB::table('users');
    }
    public function fetch_users_table()
    {
        return $this->query_users_table()->select('id', 'name', 'email', 'phone', 'gender', 'country', 'state', 'city', 'address');
    }
    public function search_query($query, $q)
    {
        $query->orWhere('name', 'like', '%' . $q . '%')
            ->orWhere('email', 'like', '%' . $q . '%')
            ->orWhere('phone', 'like', '%' . $q . '%')
            ->orWhere('gender', 'like', '%' . $q . '%')
            ->orWhere('country', 'like', '%' . $q . '%')
            ->orWhere('state', 'like', '%' . $q . '%')
            ->orWhere('city', 'like', '%' . $q . '%')
            ->orWhere('address', 'like', '%' . $q . '%');
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
                return Cache::remember($this->cache, 300, function () {
                    return $this->fetch_users_table()->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
                });
            } else {
                session()->flash('fail', 'Invalid column value!');
                return [];
            }
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->orderBy('id', 'desc')->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
    public function with_search_numbered_paginator(): object
    {
        $q = trim($this->search);
        if ($this->sort == "columns") {
            if (in_array($this->order_by[0], $this->white_list)) {
                return Cache::remember($this->cache, 300, function () {
                    return $this->fetch_users_table()->where(function ($query) use ($q) {
                        $this->search_query($query, $q);
                    })
                        ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')
                        ->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
                });
            } else {
                session()->flash('fail', 'Invalid column value!');
                return [];
            }
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $this->search_query($query, $q);
                })
                    ->orderBy('id', 'desc')
                    ->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $this->search_query($query, $q);
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
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->orderBy('id', 'desc')->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
    public function with_search_simple_paginator(): object
    {
        $q = trim($this->search);
        if ($this->sort == "columns") {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $query->orWhere('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('phone', 'like', '%' . $q . '%')
                        ->orWhere('gender', 'like', '%' . $q . '%')
                        ->orWhere('country', 'like', '%' . $q . '%')
                        ->orWhere('state', 'like', '%' . $q . '%')
                        ->orWhere('city', 'like', '%' . $q . '%')
                        ->orWhere('address', 'like', '%' . $q . '%');
                })
                    ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')
                    ->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $query->orWhere('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('phone', 'like', '%' . $q . '%')
                        ->orWhere('gender', 'like', '%' . $q . '%')
                        ->orWhere('country', 'like', '%' . $q . '%')
                        ->orWhere('state', 'like', '%' . $q . '%')
                        ->orWhere('city', 'like', '%' . $q . '%')
                        ->orWhere('address', 'like', '%' . $q . '%');
                })
                    ->orderBy('id', 'desc')
                    ->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        } else {
            return Cache::remember($this->cache, 300, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $query->orWhere('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('phone', 'like', '%' . $q . '%')
                        ->orWhere('gender', 'like', '%' . $q . '%')
                        ->orWhere('country', 'like', '%' . $q . '%')
                        ->orWhere('state', 'like', '%' . $q . '%')
                        ->orWhere('city', 'like', '%' . $q . '%')
                        ->orWhere('address', 'like', '%' . $q . '%');
                })
                    ->simplePaginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
            });
        }
    }
}
