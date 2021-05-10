<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use Livewire\Component;
use Livewire\WithPagination;

use App\Jobs\infiniteScrollCacheNextPage;

class InfiniteScroll extends Component
{
    public $page;
    public $cache_time;
    public $fetch;
    public $users;
    public $no_user;
    public $message;

    private $cache;

    public function fetch_users() : void
    {
        // if($this->page == 6) // Uncomment this for test purposes
        //     $this->no_more_users();
        if($this->no_user == 2) {
            $this->page ++;
            $this->cache = 'infinite-users.' . $this->page;
            $skip = ($this->fetch * $this->page) - $this->fetch;
            $users = Cache::remember($this->cache, $this->cache_time, function () use($skip) {
                return DB::table('users')->select('id', 'name', 'phone', 'email', 'gender')
                ->skip($skip)
                ->take($this->fetch)
                ->get();
            });
            
            $page = $this->page + 1;
            $cache = 'infinite-users.' . $page;
        
            infiniteScrollCacheNextPage::dispatchIf(!Cache::has($cache), $cache, $page, $this->fetch, $this->cache_time);
            $this->emit('appendUsers', ['users' => $users]);
        } else {
            $this->no_more_users();
        }
    }

    private function no_more_users() : void {
        $this->no_user = 1;
    }

    public function render()
    {   
        return view('livewire.infinite-scroll');
    }
}
