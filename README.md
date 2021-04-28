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
•	Set up your database in your ``.env``\
•	Run ``php artisan migrate:refresh --seed``\
•	``php artisan config:cache``\
•	Take a look at your  ``route/web.php`` to set the appropriate params\
•	Within your ``.env``, make the suitable configurations for your ``imap`` to work

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
•   Run ``php artisan serve``

## Live Version
•   <a href="https://livewire-components.provirtcomm.com" target="_blank" rel="noopener noreferrer">Livewire Components</a>
