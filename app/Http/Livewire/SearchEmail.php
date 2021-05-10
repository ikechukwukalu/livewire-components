<?php

namespace App\Http\Livewire;

use App\Jobs\searchEmailCacheNextPage;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Webklex\IMAP\Facades\Client;

class SearchEmail extends Component
{

    public $text;
    public $email;
    public $display = 'none';
    public $results = [];
    public $page = 0;
    public $scroll = 2;
    public $root;
    public $search_length = 0;
    public $commonFolders;
    public $take;
    public $cacheTime;

    private $cache;
    
    public function show_dropdown(): void
    {
        if (strlen(trim($this->text)) > 0) {
            $this->display = 'block';
            $this->search_for_emails($this->text);
        } else {
            $this->close_display();
        }
    }

    public function search_for_emails(string $text): void
    {
        $this->page++;
        $this->scroll = 2;

        $this->cache = 'searchEmail-users.' . $this->page;
        $messages = Cache::remember($this->cache, $this->cacheTime, function () use ($text) {
            $client = Client::account('default');
            $client->connect();

            $folder = $client->getFolderByPath($this->commonFolders[$this->root]);

            $result = $folder->query()
                ->from($this->email)
                ->setFetchBody(false)
                ->fetchOrderDesc()
                ->text($text)
                ->leaveUnread()
                ->paginate($this->take, $this->page);

            $client->disconnect();

            return $result;
        });

        $i = ($messages->count() * $this->page) - 1;

        if ($i < 1) {
            $this->page--;
            $this->scroll = 1;
        }

        foreach ($messages as $message) {
            $this->results[$i] = [
                'uid' => $message->uid,
                'getSubject' => trim($message->getSubject()),
            ];
            $i--;
        }

        $number_of_results = count($this->results);

        if ($this->search_length >= $number_of_results) {
            $this->no_more_emails();
        }

        $this->search_length = $number_of_results;

        $page = $this->page + 1;
        $cache = 'searchEmail-users.' . $page;

        searchEmailCacheNextPage::dispatchIf(!Cache::has($cache), $cache, $page, $this->take, $this->cacheTime, $this->commonFolders, $this->root, $this->email, $text);
    }

    public function imap_email_body(int $uid): void
    {
        try {
            $this->cache = 'emailBody-users.' . $uid;
            $message = Cache::remember($this->cache, $this->cacheTime, function () use($uid) {
                $client = Client::account('default');
                $client->connect();
                $folder = $client->getFolderByPath($this->commonFolders[$this->root]);
                $result = $folder->query()->getMessageByUid($uid);
                $client->disconnect();
                return $result;
            });

            // $attachment = $message->getAttachments();

            $body = [
                'uid' => $message->uid,
                'getSubject' => trim($message->getSubject()),
                'hasTextBody' => $message->hasTextBody(),
                'getTextBody' => htmlentities($message->getTextBody()),
                'hasHTMLBody' => $message->hasHTMLBody(),
                'getHTMLBody' => htmlentities($message->getHTMLBody(false)),
                'getAttachmentsCount' => $message->getAttachments()->count(),
                // 'getAttachments' => $attachment,
                'cacheProblem' => false
            ];

            $this->emit('emailBody', $body);
        } catch (\Illuminate\Database\QueryException | Exception | Symfony\Component\ErrorHandler\Error\FatalError $e) {
            $client = Client::account('default');
            $client->connect();
    
            $folder = $client->getFolderByPath($this->commonFolders['root']);
            $message = $folder->query()->getMessageByUid($uid);
            
            // $attachment = $message->getAttachments();
    
            $body = [
                'uid' => $message->uid,
                'getSubject' => trim($message->getSubject()),
                'hasTextBody' => $message->hasTextBody(),
                'getTextBody' => htmlentities($message->getTextBody()),
                'hasHTMLBody' => $message->hasHTMLBody(),
                'getHTMLBody' => htmlentities($message->getHTMLBody(false)),
                'getAttachmentsCount' => $message->getAttachments()->count(),
                // 'getAttachments' => $attachment,
                'cacheProblem' => true
            ];
            $client->disconnect();

            $this->emit('emailBody', $body);
        }
    }

    public function close_display(): void
    {
        $this->display = 'none';
        $this->results = [];
        $this->page = 0;
    }

    public function no_more_emails(): void
    {
        session()->flash('info', 'No more emails to load');
    }

    public function render()
    {
        return view('livewire.search-email');
    }
}
