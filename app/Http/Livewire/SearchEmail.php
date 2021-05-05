<?php

namespace App\Http\Livewire;

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

    private $common_folders = [
        'root' => 'INBOX',
        'junk' => 'INBOX.Spam',
        'drafts' => 'INBOX.Drafts',
        'sent' => 'INBOX.Sent',
        'trash' => 'INBOX.Trash',
    ];

    public function show_dropdown() : void
    {
        if (strlen(trim($this->text)) > 0) {
            $this->display = 'block';
            $this->search_for_emails($this->text);
        } else {
            $this->close_display();
        }
    }

    public function search_for_emails($text) : void
    {
        $this->page++;
        $this->scroll = 2;

        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolderByPath($this->common_folders[$this->root]);
        $messages = $folder->query()
            ->from($this->email)
            ->setFetchBody(false)
            ->fetchOrderDesc()
            ->text($text)
            ->leaveUnread()
            ->paginate(5, $this->page);

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

        if($this->search_length >= $number_of_results)
            $this->no_more_emails();
        
        $this->search_length = $number_of_results;

        $client->disconnect();
    }

    public function imap_email_body($uid) : void
    {
        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolderByPath($this->common_folders['root']);
        $message = $folder->query()->getMessageByUid($uid);
        $attachment = $message->getAttachments();

        $body = [
            'uid' => $message->uid,
            'getSubject' => trim($message->getSubject()),
            'hasTextBody' => $message->hasTextBody(),
            'getTextBody' => htmlentities($message->getTextBody()),
            'hasHTMLBody' => $message->hasHTMLBody(),
            'getHTMLBody' => htmlentities($message->getHTMLBody(false)),
            'getAttachmentsCount' => $message->getAttachments()->count(),
            // 'getAttachments' => $attachment,
        ];

        $client->disconnect();
        $this->emit('emailBody', $body);
    }

    public function close_display() : void {
        $this->display = 'none';
        $this->results = [];
        $this->page = 0;
    }

    public function no_more_emails() : void {
        session()->flash('info', 'No more emails to load');
    }

    public function render()
    {
        return view('livewire.search-email');
    }
}
