laravel-api
===========

## Installation
To add Laravel-API to your Laravel application follow this three steps:

Add the following to your `composer.json` file:
```
"repositories": [{
  "type": "vcs",
  "url": "https://github.com/johnnygreen/laravel-api"
}],
"require": [
  "johnnygreen/Laravel-api" : "dev-master"
]
```

Then run `composer update` or `composer install` if you have not already installed packages.

Add below to the `providers` array in `app/config/app.php` configuration file (add the end):
```
'Johnnygreen\LaravelApi\LaravelApiServiceProvider',
```

Add below to the `aliases` array in `app/config/app.php` configuration file (add the end):
```
'LaravelApi'		=> 'Johnnygreen\LaravelApi\Facades\LaravelApi',
```

## Configuration

You will want to run the following command to publish the config to your application, otherwise it will be overwritten in updates.
```
php artisan config:publish johnnygreen/laravel-api
```
