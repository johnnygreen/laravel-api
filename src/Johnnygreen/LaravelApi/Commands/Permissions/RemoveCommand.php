<?php namespace Johnnygreen\LaravelApi\Commands\Permissions;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use Johnnygreen\LaravelApi\Auth\Permission;

class RemoveCommand extends Command {

  protected $name = 'permission:remove';
  protected $description = 'Remove permission.';
  
  public function fire()
  {
    $name = $this->argument('name');
    
    $permission = Permission::where('name', '=', $name)->first();
    
    if ( ! is_null($permission))
    {
      try
      {
        $permission->users()->detach();
        $permission->groups()->detach();
        $permission->delete();
      }
      catch(\Exception $e)
      {
        $this->error($e->getMessage());
      }
      
      $this->info("{$name} permission removed.");
    }
    else
    {
      $this->comment("{$name} permission does not exist.");
    }

  }
  
  protected function getArguments()
  {
    return array(
      ['name', InputArgument::REQUIRED, 'The name of the permission being removed.'],
    );
  }

}