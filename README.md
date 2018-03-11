# Final Filer

## By Benjamin COURTINE & Neil RICHTER

# musti

There is currently a live demo available at : http://musti.keepthis4.me

## Database

Make sure you replace the credentials in `config.php` with yours, so queries can work.

or
```php
$config = [
    'db' => [
        'name'     => 'dbname',
        'user'     => 'username',
        'password' => 'password',
        'host'     => '127.0.0.1',
        'port'     => null,
    ]
];
```

## Users
New users will have their directory named after their username in the directory `uploads`.

```
users/
├── .htaccess
└── johndoe
    ├── file.jpg
    └── dir
        └── file.pdf
```

## Directories access
in the directory `uploads/` there is a `.htaccess` (Apache) that will prevent any user, logged in or not to download or display in the browser any file that doesn't belong to him. It will return a 403 Forbiden error instead.

Installation
============

Run
```cp config/config.php.dist config/config.php```

Add your config to config.php
Run
```composer install```
or
```php composer.phar install```

You can get composer here : <https://getcomposer.org/download/>

