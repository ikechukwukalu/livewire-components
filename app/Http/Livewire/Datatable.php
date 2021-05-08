<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Livewire\Component;
use Livewire\WithPagination;

use App\Jobs\cachePage;
use App\Jobs\cacheLastPage;
use App\Jobs\cacheLastThreePages;

class Datatable extends Component
{
    use WithPagination;

    public $columns;
    public $page_options;
    public $order_by;
    public $search = null;
    public $fetch = 5;
    public $column = null;
    public $order = null;
    public $sort = null;
    public $maxP;
    public $total;
    public $set;
    public $current_page;
    public $last_page;
    public $load_state = 'Initializing datatable component...';
    public $white_list = [];
    public $cache_time;

    protected $queryString = ['search', 'fetch', 'column', 'order', 'sort'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteUser' => 'delete_user', 'reloadDatatable' => 'make_datatable'];

    private $users = [];
    private $cache;

    public function __construct() {
        ini_set('max_execution_time', '120');
    }

    /**
     * Hooks
     */
    public function mount()
    {
        foreach ($this->columns as $column) {
            $this->white_list[] = $column['sort'];
        }
    }

    /**
     * Private Query Functions
     */
    private function query_users_table()
    {
        return DB::table('users');
    }
    private function fetch_users_table()
    {
        return $this->query_users_table()->select('id', 'name', 'email', 'phone', 'gender', 'country', 'state', 'city', 'address');
    }
    private function search_query($query, $q) {
        $query->orWhere('name', 'like', '%' . $q . '%')
            ->orWhere('email', 'like', '%' . $q . '%')
            ->orWhere('phone', 'like', '%' . $q . '%')
            ->orWhere('gender', 'like', '%' . $q . '%')
            ->orWhere('country', 'like', '%' . $q . '%')
            ->orWhere('state', 'like', '%' . $q . '%')
            ->orWhere('city', 'like', '%' . $q . '%')
            ->orWhere('address', 'like', '%' . $q . '%');
    }

    /**
     * Private Functions
     */
    private function export_data_from_table($type = 'pdf', $json = false): void
    {
        $body = [];
        $total = $this->get_user_rows();
        $users = $total > $this->maxP ? $this->implement_simple_paginator() : $this->implement_numbered_paginator();

        if ($json) {
            $body[] = [
                'name' => 'NAME', 'email' => 'EMAIL', 'phone' => 'PHONE', 'gender' => 'GENDER',
                'country' => 'COUNTRY', 'state' => 'STATE', 'city' => 'CITY', 'address' => 'ADDRESS',
            ];
        } else {
            $body[] = [
                'NAME', 'EMAIL', 'PHONE', 'GENDER',
                'COUNTRY', 'STATE', 'CITY', 'ADDRESS',
            ];
        }

        foreach ($users as $user) {
            if ($json) {
                $body[] = [
                    'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone, 'gender' => $user->gender,
                    'country' => $user->country, 'state' => $user->state, 'city' => $user->city, 'address' => $user->address,
                ];
            } else {
                $body[] = [
                    $user->name, $user->email, $user->phone, $user->gender,
                    $user->country, $user->state, $user->city, $user->address,
                ];
            }

        }
        
        $this->emit('docMake', ['type' => $type, 'body' => $json ? json_encode($body) : $body]);
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
                return Cache::remember($this->cache, $this->cache_time, function () {
                    return $this->fetch_users_table()->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->paginate($this->fetch);
                });
            } else {
                session()->flash('fail', 'Invalid column value!');
                return [];
            }
        } elseif ($this->sort == "latest") {
            return $this->fetch_users_table()->orderBy('id', 'desc')->paginate($this->fetch);
        } else {
            return $this->fetch_users_table()->paginate($this->fetch);
        }
    }
    private function with_search_numbered_paginator(): object
    {
        $q = trim($this->search);
        if ($this->sort == "columns") {
            if (in_array($this->order_by[0], $this->white_list)) {
                return Cache::remember($this->cache, $this->cache_time, function () {
                    return $this->fetch_users_table()->where(function ($query) use ($q) {
                        $this->search_query($query, $q);
                    })
                    ->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')
                    ->paginate($this->fetch);
                });
            } else {
                session()->flash('fail', 'Invalid column value!');
                return [];
            }
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $this->search_query($query, $q);
                })
                ->orderBy('id', 'desc')
                ->paginate($this->fetch);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->where(function ($query) use ($q) {
                    $this->search_query($query, $q);
                })
                ->paginate($this->fetch);
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
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->orderBy($this->order_by[0], $this->order_by[1] ? 'asc' : 'desc')->simplePaginate($this->fetch);
            });
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->orderBy('id', 'desc')->simplePaginate($this->fetch);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
                return $this->fetch_users_table()->simplePaginate($this->fetch);
            });
        }
    }
    private function with_search_simple_paginator(): object
    {
        $q = trim($this->search);
        if ($this->sort == "columns") {
            return Cache::remember($this->cache, $this->cache_time, function () {
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
                ->simplePaginate($this->fetch);
            });
        } elseif ($this->sort == "latest") {
            return Cache::remember($this->cache, $this->cache_time, function () {
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
                ->simplePaginate($this->fetch);
            });
        } else {
            return Cache::remember($this->cache, $this->cache_time, function () {
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
                ->simplePaginate($this->fetch);
            });
        }
    }
    private function get_user_rows(): int
    {
        $user_rows = DB::table('user_rows')->first();
        return isset($user_rows->number) && is_int($user_rows->number) ? $user_rows->number : 0;
    }
    private function cache_page() :void 
    {
        $current_page = $this->page;
        for ($i = $this->page; $i >= ($current_page - 2); $i --) {
            if($i < 1)
                break;

            $cache = explode('.', $this->cache);
            $cache[6] = $i;
            $cache = implode('.', $cache);

            cachePage::dispatchIf(!Cache::has($cache), $cache, $i, ($this->total > $this->maxP));
        }
        for ($i = $this->page; $i <= ($current_page + 2); $i ++) {
                
            $cache = explode('.', $this->cache);
            $cache[6] = $i;
            $cache = implode('.', $cache);

            cachePage::dispatchIf(!Cache::has($cache), $cache, $i, ($this->total > $this->maxP));
        }
    }

    /**
     * Public Functions
     */
    public function gotoPage(int $page): void
    {
        $this->page = $page;
        $this->emit('showPage', $this->page);
    }
    public function previousPage(): void
    {
        $this->page > 1 ? $this->page -= 1 : 1;
        $this->emit('showPage', $this->page);
    }
    public function nextPage(): void
    {
        $this->page += 1;
        $this->emit('showPage', $this->page);
    }
    public function resort(string $column): void
    {
        $this->order_by = [$column, $column != $this->order_by[0] ? true : !$this->order_by[1]];
    }
    public function delete_user(int $id): void
    {
        $this->query_users_table()->where('id', $id)->delete();

        $user_rows = DB::table('user_rows')->first();
        $total = $user_rows->number - 1;
        DB::table('user_rows')->update(['number' => $total]);
        
        $this->emit('cellVisibility');
    }
    public function pdf_make(): void
    {
        $this->export_data_from_table();
    }
    public function table_to_excel(): void
    {
        $this->export_data_from_table('excel');
    }
    public function export_to_csv(): void
    {
        $this->export_data_from_table('csv', true);
    }
    public function make_datatable(): void
    {
        $this->load_state = 'No matching records';
        $this->total = $this->get_user_rows();

        if ($this->total > $this->maxP) {
            $this->sort = $this->sort == 'columns' || $this->sort == 'latest' ? 'latest' : null;
            $this->column = null;
            $this->order = null;
        } else {
            if (isset($this->order_by[0])) {
                $this->column = $this->order_by[0];
            }

            if (isset($this->order_by[1])) {
                $this->order = $this->order_by[1] ? 'asc' : 'desc';
            }
        }

        if (!in_array($this->fetch, $this->page_options)) {
            $this->fetch = $this->page_options[0];
        }

        $this->cache = 'users.' . $this->fetch . '.' . $this->search . '.' . $this->column . '.' . $this->order . '.' . $this->sort . '.' . $this->page;

        $this->users = $this->total > $this->maxP ? $this->implement_simple_paginator() : $this->implement_numbered_paginator();
        $this->current_page = (($this->page * $this->fetch) - $this->fetch) + 1;
        $remainder = $this->total % $this->fetch;
        $pages = $remainder < 1 ? ($this->total - $remainder) / $this->fetch : (($this->total - $remainder) / $this->fetch) + 1;
        $this->set = $this->page < $pages ? $this->page * $this->fetch : $this->total;
        $this->last_page = (int) ceil($this->total / $this->fetch);
        
        /***
         * Cache::flush() - clear cache
         * Switch `cacheLastPage` for `cacheLastThreePages to cache the last 3 pages instead
        */
        
        $cache = explode('.', $this->cache);
        $cache[6] = $this->last_page;
        $cache = implode('.', $cache);

        cacheLastPage::dispatchIf(!Cache::has($cache), $this->cache, $this->last_page, ($this->total > $this->maxP));
        $this->cache_page();

        $this->emit('cellVisibility');
    }
    public function render()
    {
        $this->make_datatable();
        return view('livewire.datatable', [
            'users' => $this->users,
        ]);
    }
}
