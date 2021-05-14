<?php

namespace App\Listeners;

use App\Events\datatableEvent;
use App\Jobs\datatableCachePage;

use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class datatableCachePageListener implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection = 'database';

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'default';

    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 3;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  datatableEvent  $event
     * @return void
     */
    public function handle(datatableEvent $event)
    {
        //
    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  \App\Events\datatableEvent  $event
     * @return bool
     */
    public function shouldQueue(datatableEvent $event)
    {
        //
        $current_page = $event->page;
        for ($i = $event->page; $i >= ($current_page - 2); $i--) {
            if ($i < 1) {
                break;
            }

            $cache = explode('.', $event->cache);
            $cache[6] = $i;
            $cache = implode('.', $cache);

            datatableCachePage::dispatchIf(!Cache::has($cache), $cache, $i, $event->simplePaginate, $event->order_by, $event->cache_time, $event->white_list);
        }
        for ($i = $event->page; $i <= ($current_page + 2); $i++) {

            $cache = explode('.', $event->cache);
            $cache[6] = $i;
            $cache = implode('.', $cache);

            datatableCachePage::dispatchIf(!Cache::has($cache), $cache, $i, $event->simplePaginate, $event->order_by, $event->cache_time, $event->white_list);
        }
    }
}
