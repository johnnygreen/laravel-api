<?php namespace Johnnygreen\LaravelApi\Commands\Permissions;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use Johnnygreen\LaravelApi\Auth\Permission;

class SeedCommand extends Command {

  protected $name = 'permission:seed';
  protected $description = 'Seed permissions table from the routes lookup.';
  
  public function fire()
  {
    foreach(\Route::getRoutes() as $route)
    {
      $name = $route->getAction();
      $permission = Permission::where('name', '=', $name)->first();
      
      if (is_null($permission))
      {
        $permission = new Permission;
        $permission->name = $name;
        
        try
        {
          $permission->save();
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }
        
        $this->info("{$name} permission added.");
      }
      else
      {
        $this->comment("{$name} permission exists.");
      }
    }
  }

}