Laravel-API
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
  "johnnygreen/laravel-api" : "dev-master"
]
```

Then run `composer update` or `composer install` if you have not already installed packages.

Add below to the `providers` array in `app/config/app.php` configuration file (at the end):
```
'Johnnygreen\LaravelApi\LaravelApiServiceProvider',
```

Add below to the `aliases` array in `app/config/app.php` configuration file (at the end):
The LaravelApi Facade currently doesn't do much, but we might see it in the future.
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

Coming Soon -- hoping to make 'Laravel-API' a bit more flexible with config options.
```
artisan config:publish johnnygreen/laravel-api
```

## Usage

I extend my API Controllers with the following ApiController.  When someone is using an access_token, it will log them in automatically.

Notice the "use \Johnnygreen\LaravelApi\RestfulJsonApi;".  That trait gives our ApiController some very helpful methods.
```
<?php namespace Api;

use Johnnygreen\LaravelApi\Auth\Token;

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

// /tokens
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
### Route based Visibility

A Permission exists in the permissions table for "ProductsController@index" to be able to access the index method.
Also a User must be associated directly with that Permission, or one of a User's Groups must be associated with that Permission.

If I want to require Authorization on a controller I do:
```
<?php namespace Api;

use Product;

// /products
class ProductsController extends ApiController {

  public function __construct()
  {
    parent::__construct();
    
    // set the filter to require authorization for index route
    $this->beforeFilter('LaravelApi.auth', ['only' => 'index']);
  }
  
  // get full products list
  public function index()
  {
    return $this->okay(Product::enabled()->get());
  }
  
  // only view one product at a time
  public function show($id)
  {
    $product = Product::enabled()->find($id);

    return ! is_null($product)
         ? $this->okay($product)
         : $this->notFound();
  }

}
```
### Permission based Visibility

A Permission exists in the permissions table for "Full Inventory Visibility". Also a User must be associated 
directly with that Permission, or one of a User's Groups must be associated with that Permission.

If I want to require Authorization for certain levels of visibility I do:
```
<?php namespace Api;

use Product;
use Serializer\SafeInventory;

// /products/:id/inventory
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

## Commands

Some fun commands that come with this package:
```
user
  user:add              Add user.
  user:list             List users.
  user:remove           Remove user.
  
permission
  permission:add        Add permission.
  permission:grant      Grant a permission to a User.
  permission:list       List permissions.
  permission:remove     Remove permission.
  permission:revoke     Revoke a permission from a User.
  permission:seed       Seed permissions table from the routes lookup.

group
  group:add             Add group.
  group:join            Add a User to a Group.
  group:leave           Remove a User from a Group.
  group:list            List groups.
  group:remove          Remove group.
```

## Issues

I threw this documentation together rather quickly.  If you run into any issues trying to setup or use the package, please file an issue with this repo.  Thanks.
