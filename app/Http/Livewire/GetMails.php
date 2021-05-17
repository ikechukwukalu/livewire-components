<?php

namespace App\Http\Livewire;

use App\Jobs\getEmailCacheNextPage;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use Webklex\IMAP\Facades\Client;

class GetMails extends Component
{
    use WithPagination;

    public $webmails;
    public $email;
    public $root;
    public $take;
    public $cacheTime;
    public $load_state = 'Initializing email component...';
    public $commonFolders;

    private $cache;

    private $links = null;

    protected $paginationTheme = 'bootstrap';

    public function imap_emails(): void
    {   
        try {
            $this->webmails = [];
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

            $this->links = $messages->links();
            $i = $messages->count() - 1;
            foreach ($messages as $message) {
                $this->webmails[$i] = [
                    'uid' => $message->uid,
                    'getSubject' => trim($message->getSubject()),
                ];
    
                $i--;
            }
            if (count($this->webmails) < 1) {
                $this->load_state = 'No emails found';
            }
    
            $page = $this->page + 1;
            $cache = 'getEmail-users.' . $page;
    
            getEmailCacheNextPage::dispatchIf(!Cache::has($cache), $cache, $page, $this->take, $this->cacheTime, $this->commonFolders, $this->root, $this->email);
        } catch (\Webklex\PHPIMAP\Exceptions\ConnectionFailedException | \Exception $e) {
            $this->load_state = 'We could not make a connection';
            session()->flash('danger', 'Connection failed');
        }
    }

    public function gotoPage(int $page): void
    {
        $this->page = $page;
        $this->imap_emails();
    }

    public function previousPage(): void
    {
        $this->page > 1 ? $this->page -= 1 : 1;
        $this->imap_emails();
    }

    public function nextPage(): void
    {
        $this->page += 1;
        $this->imap_emails();
    }

    public function cacheProblem() : void {
        session()->flash('danger', 'We encountered some problems while loading this email. It could not be cached. It seems to contain embedded images and files that are quite heavy when loading.');
        $this->imap_emails();
    }

    public function render()
    {
        return view('livewire.get-mails', [
            'paginators' => $this->links,
        ]);
    }
}
