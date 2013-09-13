laravel-api
===========
For now this is just an example API using Laravel 4.  Learn.

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

Run the migrations for the package -- these databases will be created:
```
users
groups
permissions
group_user
permission_user
group_permission
```
```
artisan migrate:install
artisan migrate --package="johnnygreen/laravel-api"
```

## Configuration

You will want to run the following command to publish the config to your application, otherwise it will be overwritten in updates.
```
artisan config:publish johnnygreen/laravel-api
```

## Usage

I extend my API Controllers with the following ApiController.  When someone is using an access_token, it will log them in automatically.
```
<?php namespace Api;

use \Johnnygreen\LaravelApi\Auth\Token;

class ApiController extends \Controller {

  use \Johnnygreen\LaravelApi\RestfulJsonApi;

  public function __construct()
  {
    // when this happens, the access token is refreshed
    // this will log in access_token users automatically
    if ($access_token = Token::extractFromHeader())
    {
      \Auth::once(['access_token' => $access_token]);
    }
  }

}
```

Here is a Controller I created to issue tokens.
```
<?php namespace Api;

use Johnnygreen\LaravelApi\Auth\Token;

class TokensController extends ApiController {

  public function store()
  {
    $input = \Input::get('credentials') ?: [];

    $validator = \Validator::make($input, [
      'username' => ['required'],
      'password' => ['required']
    ]);

    if ($validator->passes())
    {
      if (\Auth::once($input))
      {
        try
        {
          $token = Token::renewOrCreate(\Auth::user());
        }
        catch (\Exception $e)
        {
          return $this->internalServerError($e->getMessage());
        }

        return $this->created($token);
      }
      else
      {
        return $this->unauthorized();
      }
    }
    else
    {
      return $this->badRequest($validator);
    }
  }

}
```

If I want to require Authorization on a controller I do:
```

```

If I want to require Authorization for certain levels of visibility I do:
```
<?php namespace Api;

use Product;
use Serializer\SafeInventory;

class InventoryController extends ApiController {

  public function index($product_id)
  {
    $product = Product::enabled()->with('inventory')->find($product_id);

    return ( ! is_null($product) and ! is_null($product->inventory))
         ? $this->okay(
             $inventory = \Auth::check("Full Inventory Visibility")
                        ? $product->inventory
                        : new SafeInventory($product->inventory)
           )
         : $this->notFound();
  }

}
```
