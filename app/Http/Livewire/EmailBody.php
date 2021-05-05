<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Webklex\IMAP\Facades\Client;

class EmailBody extends Component
{

    public $email;
    public $root;

    private $common_folders = [
        'root' => 'INBOX',
        'junk' => 'INBOX.Spam',
        'drafts' => 'INBOX.Drafts',
        'sent' => 'INBOX.Sent',
        'trash' => 'INBOX.Trash',
    ];

    public function imap_email_body($uid) : void
    {
        $client = Client::account('default');
        $client->connect();

        $folder = $client->getFolderByPath($this->common_folders[$this->root]);
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

    public function render()
    {
        return view('livewire.email-body');
    }
}
