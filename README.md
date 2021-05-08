A LARAVEL 8 APPLICATION UTILIZING LIVEWIRE
## Laravel Packages Used
•	<a href="https://github.com/Webklex/laravel-imap">https://github.com/Webklex/laravel-imap</a>\
•	<a href="https://github.com/livewire/livewire">https://github.com/livewire/livewire</a>\
•	<a href="https://github.com/barryvdh/laravel-debugbar">https://github.com/barryvdh/laravel-debugbar</a>
## Quick Start
•	Clone this repo\
•	``copy .env.example .env``\
•	``composer install``\
•	``php artisan key:generate``\
•	Set up a database in your ``.env``\
•	Run ``php artisan migrate:refresh --seed``\
•	``php artisan config:cache``\
•	Take a look at your  ``route/web.php`` to set the appropriate params

```
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('datatable', function () {
    /**
     * Table Header | Footer columns
     */
    $columns = [
        ['name' => 'name', 'sort' => 'name'],
        ['name' => 'email', 'sort' => 'email'],
        ['name' => 'phone', 'sort' => 'phone'],
        ['name' => 'gender', 'sort' => 'gender'],
        ['name' => 'country', 'sort' => 'country'],
        ['name' => 'state', 'sort' => 'state'],
        ['name' => 'city', 'sort' => 'city'],
        ['name' => 'address', 'sort' => 'address'],
    ];

    /**
     * ['column', 'true - asc|false - desc'] is effective if [sort] is set to columns
     * To prevent SQL injection for this example I've whitelisted the necessary columns
     * by including them into the $white_list array
     */
    $order_by = [$columns[0]['sort'], true];

    /**
     * Dropdown options for the number of rows that can be fetched
     */
    $page_options = [5, 10, 15, 25, 50, 100];

    /**
     * Default page_options value
     */
    $fetch = $page_options[0];

    /**
     * Sort Table
     * -----------
     * columns | not recommended for large records exceeding 5k,
     * latest | speed is very good,
     * null | speed is the fastest
     */
    $sort = 'columns';

    /**
     * Max allowed for numbered paginator | switch to simple paginator
     */
    $maxP = 5000;

    return view('datatable', [
        'columns' => $columns,
        'order_by' => $order_by,
        'page_options' => $page_options,
        'fetch' => $fetch,
        'sort' => $sort,
        'maxP' => $maxP,
    ]);
})->name('datatable');

Route::get('mail', function () {
    /*
     *
     *
     * private $common_folders = [
     *      'root' => 'INBOX',
     *      'junk' => 'INBOX.Spam',
     *      'drafts' => 'INBOX.Drafts',
     *      'sent' => 'INBOX.Sent',
     *      'trash' => 'INBOX.Trash'
     * ]
     */

    return view('get-mails', [
        'sender' => 'info@provirtcomm.com', 
        'folder' => 'root'
    ]);
})->name('get-mails');

Route::prefix('sortable')->group(function () {
    Route::get('basic', function () {
        return view('sortable-basic');
    })->name('sortable-basic');

    Route::get('complex', function () {
        return view('sortable-complex');
    })->name('sortable-complex');
});
```

•	Within your ``.env`` make the suitable configurations for your ``imap`` to work

```
IMAP_HOST=mail.example.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
IMAP_VALIDATE_CERT=true
IMAP_USERNAME=livewire@example.com
IMAP_PASSWORD="xxxxxxxxxx"
IMAP_DEFAULT_ACCOUNT=default
IMAP_PROTOCOL=imap
```

•	Run ``npm install``, ``npm run watch`` for development\
•   Run ``npm run prod`` for production\
•   Run ``php artisan serve``\
•   Run ``php artisan queue:work``
