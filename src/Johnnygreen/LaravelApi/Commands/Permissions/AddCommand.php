<?php namespace Johnnygreen\LaravelApi\Commands\Permissions;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use Johnnygreen\LaravelApi\Auth\Permission;

class AddCommand extends Command {

  protected $name = 'permission:add';
  protected $description = 'Add permission.';
  
  public function fire()
  {
    $name = $this->argument('name');
    
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
  
  protected function getArguments()
  {
    return array(
      ['name', InputArgument::REQUIRED, 'The name of the permission being added.'],
    );
  }

}