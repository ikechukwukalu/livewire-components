<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Webklex\IMAP\Facades\Client;

use Illuminate\Support\Facades\Cache;

class EmailBody extends Component
{

    public $email;
    public $root;
    public $cacheTime;
    public $commonFolders;

    private $cache;

    public function imap_email_body($uid) : void
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

    public function render()
    {
        return view('livewire.email-body');
    }
}
