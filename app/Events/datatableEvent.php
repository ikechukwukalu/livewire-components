<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class datatableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $cache = null;
    public $simplePaginate = true;
    public $params = [];

    public $cache_time;
    public $order_by;
    public $search = null;
    public $fetch = 5;
    public $column = null;
    public $order = null;
    public $sort = null;
    public $white_list = [];
    public $page = null;
    public $last_page = null;

    public function __construct(string $cache, int $page, bool $simplePaginate, array $order_by, int $cache_time, array $white_list, int $last_page)
    {
        //
        $this->cache = $cache;
        $this->simplePaginate = $simplePaginate;
        $this->order_by = $order_by;
        $this->cache_time = $cache_time;
        $this->white_list = $white_list;
        $this->last_page = $last_page;

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
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
