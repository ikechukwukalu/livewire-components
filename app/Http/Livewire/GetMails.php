<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Webklex\IMAP\Facades\Client;

class GetMails extends Component
{
    use WithPagination;

    public $webmails;
    public $email;
    public $root;

    private $common_folders = [
        'root' => 'INBOX',
        'junk' => 'INBOX.Spam',
        'drafts' => 'INBOX.Drafts',
        'sent' => 'INBOX.Sent',
        'trash' => 'INBOX.Trash',
    ];
    private $links = null;

    protected $paginationTheme = 'bootstrap';

    public function imap_emails()
    {
        $client = Client::account('default');
        $client->connect();

        $this->webmails = [];

        $folder = $client->getFolderByPath($this->common_folders[$this->root]);
        $messages = $folder->query()
            // ->since('01.03.2021') // created_at
            // ->before('14.04.2021') // completed_at
            ->all()
            ->from($this->email)
            // ->to($this->email)
            ->setFetchBody(false)
            ->fetchOrderDesc()
            ->leaveUnread()
            ->paginate(5, $this->page);

        $this->links = $messages->links();
        $i = $messages->count() - 1;
        foreach ($messages as $message) {
            $this->webmails[$i] = [
                'uid' => $message->uid,
                'getSubject' => trim($message->getSubject()),
            ];

            $i--;
        }

        $client->disconnect();
    }

    public function gotoPage($page)
    {
        $this->page = $page;
        $this->imap_emails();
    }

    public function previousPage()
    {
        $this->page > 1 ? $this->page -= 1 : 1;
        $this->imap_emails();
        return true;
    }

    public function nextPage()
    {
        $this->page += 1;
        $this->imap_emails();
        return true;
    }

    public function render()
    {
        return view('livewire.get-mails', [
            'paginators' => $this->links,
        ]);
    }
}
