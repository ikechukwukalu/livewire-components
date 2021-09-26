<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mailLWController extends Controller
{
    /*
     * Max allowed time for cached query to last
     */
    private $cache_time = 300;

    /*
     * Amount of email items to fetch
     */
    private $fetch = 5; // Recommended

    /*
     * Common folders array
     */
    private $common_folders = [
        'root' => 'INBOX',
        'junk' => 'INBOX.Spam',
        'drafts' => 'INBOX.Drafts',
        'sent' => 'INBOX.Sent',
        'trash' => 'INBOX.Trash',
    ];

    /*
     * Sender email
     */
    private $sender = 'info@example.com';

    /*
     * Root folder
     */
    private $folder = 'root';

    public function imap() {
        return view('get-mails', [
            'sender' => $this->sender,
            'folder' => $this->folder,
            'cache_time' => $this->cache_time,
            'fetch' => $this->fetch,
            'common_folders' => $this->common_folders
        ]);
    }
}
