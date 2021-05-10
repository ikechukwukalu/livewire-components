<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Webklex\IMAP\Facades\Client;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class getEmailCacheNextPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $page;
    private $cache;
    private $cacheTime;
    private $take;
    private $email;
    private $root;
    private $commonFolders;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $cache, int $page, int $take, int $cacheTime, array $commonFolders, string $root, string $email)
    {
        //
        $this->cache = $cache;
        $this->page = $page;
        $this->take = $take;
        $this->cacheTime = $cacheTime;
        $this->commonFolders = $commonFolders;
        $this->root = $root;
        $this->email = $email;
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
        
        $this->cache = 'getEmail-users.' . $this->page;
        $messages = Cache::remember($this->cache, $this->cacheTime, function () {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolderByPath($this->commonFolders[$this->root]);

            $result = $folder->query()
            // ->since('01.03.2021') // created_at
            // ->before('14.04.2021') // completed_at
                ->all()
                ->from($this->email)
            // ->to($this->email)
                ->setFetchBody(false)
                ->fetchOrderDesc()
                ->leaveUnread()
                ->paginate($this->take, $this->page);

            $client->disconnect();

            return $result;
        });

        $time_elapsed_secs = microtime(true) - $start;
        echo "Part 2: " . $time_elapsed_secs . ", Fetched next list of emails " . " \n";
    }
}
