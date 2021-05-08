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

class cacheLastThreePages implements ShouldQueue
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

    public function __construct(string $cache, int $last_page, bool $simplePaginate)
    {
        //
        $this->cache = $cache;
        $this->last_page = $last_page;
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
        $this->page = $last_page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->loop_last_three_pages();
    }
    
    private function loop_last_three_pages() {
        DB::disableQueryLog();
        $start = microtime(true);

        $limit = $this->last_page - 3;
        if ($this->simplePaginate) {
            for ($i = $this->last_page; $i > $limit; $i --) {
                $this->params[6] = $i;
                $this->page = $i;
                $this->cache = implode('.', $this->params);

                $time_elapsed_secs = microtime(true) - $start;
                echo "Part 1: " . $time_elapsed_secs . ", Cache key: " . $this->cache . " \n";

                $this->implement_simple_paginator();

                $time_elapsed_secs = microtime(true) - $start;
                echo "Part 2: " . $time_elapsed_secs . ", Paginator: " . "implement_simple_paginator" . " \n";
            }
        } else {
            for ($i = $this->last_page; $i > $limit; $i --) {
                $this->params[6] = $i;
                $this->page = $i;
                $this->cache = implode('.', $this->params);

                $time_elapsed_secs = microtime(true) - $start;
                echo "Part 1: " . $time_elapsed_secs . ", Cache key: " . $this->cache . " \n";

                $this->implement_numbered_paginator();

                $time_elapsed_secs = microtime(true) - $start;
                echo "Part 2: " . $time_elapsed_secs . ", Paginator: " . "implement_numbered_paginator" . " \n";
            }
        }
    }
    private function query_users_table()
    {
        return DB::table('users');
    }
    private function fetch_users_table()
    {
        return $this->query_users_table()->select('id', 'name', 'email', 'phone', 'gender', 'country', 'state', 'city', 'address');
    }
    private function search_query($query, $q)
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
    private function implement_numbered_paginator(): object
    {
        if (trim($this->search) == "") {
            return $this->no_search_numbered_paginator();
        } else {
            return $this->with_search_numbered_paginator();
        }

    }
    private function no_search_numbered_paginator(): object
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
            return $this->fetch_users_table()->orderBy('id', 'desc')->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
        } else {
            return $this->fetch_users_table()->paginate($perPage = $this->fetch, $columns = ['*'], $pageName = 'page', $page = $this->page);
        }
    }
    private function with_search_numbered_paginator(): object
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
    private function implement_simple_paginator(): object
    {
        if (trim($this->search) == "") {
            return $this->no_search_simple_paginator();
        } else {
            return $this->with_search_simple_paginator();
        }
    }
    private function no_search_simple_paginator(): object
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
    private function with_search_simple_paginator(): object
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
