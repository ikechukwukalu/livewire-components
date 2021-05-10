<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class infiniteScrollCacheNextPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $page;
    private $cache;
    private $cache_time;
    private $fetch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $cache, int $page, int $fetch, int $cache_time)
    {
        //
        $this->cache = $cache;
        $this->page = $page;
        $this->fetch = $fetch;
        $this->cache_time = $cache_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        DB::disableQueryLog();
        $start = microtime(true);

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 1: " . $time_elapsed_secs . ", Cache key: " . $this->cache . " \n";

        $skip = ($this->fetch * $this->page) - $this->fetch;
        Cache::remember($this->cache, $this->cache_time, function () use($skip) {
            return DB::table('users')->select('id', 'name', 'phone', 'email', 'gender')
            ->skip($skip)
            ->take($this->fetch)
            ->get();
        });

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Fetched next list of users " . " \n";
    }
}
