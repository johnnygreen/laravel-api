<?php namespace Johnnygreen\LaravelApi\Commands\Permissions;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Johnnygreen\LaravelApi\Auth\Permission;
use Johnnygreen\LaravelApi\Auth\User;
use Johnnygreen\LaravelApi\Auth\Group;

class RevokeCommand extends Command {

  protected $name = 'permission:revoke';
  protected $description = 'Revoke a permission from a User.';
  
  public function fire()
  {
    $name = $this->argument('name');
    $permission = Permission::where('name', '=', $name)->first();

    if (is_null($permission))
    {
      $this->comment("{$name} is not a valid permission.");
      exit;
    }

    if ($username = $this->option('user'))
    {
      $user = User::where('username', '=', $username)->first();

      if (is_null($user))
      {
        $this->comment("{$username} is not a valid user.");
        exit;
      }
  
      $already_granted = $user->permissions()->where('name', '=', $name)->first();
  
      if ( ! is_null($already_granted))
      {
        try
        {
          $user->permissions()->detach($permission);
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }
        
        $this->info("{$name} revoked from {$username}.");
      }
      else
      {
        $this->comment("{$name} permission does not belong to {$username}.");
      }
      
      return;
    }
    
    if ($groupname = $this->option('group'))
    {
      $group = Group::where('name', '=', $groupname)->first();

      if (is_null($group))
      {
        $this->comment("{$groupname} is not a valid group.");
        exit;
      }
  
      $already_granted = $group->permissions()->where('name', '=', $name)->first();
  
      if ( ! is_null($already_granted))
      {
        try
        {
          $group->permissions()->detach($permission);
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }
        
        $this->info("{$name} revoked from {$groupname}.");
      }
      else
      {
        $this->comment("{$name} permission does not belong to {$groupname}.");
      }
      
      return;
    }
    
    $this->comment('Please use the group or user option.');
  }
  
  protected function getArguments()
  {
    return [
      ['name', InputArgument::REQUIRED, 'The name of the permission being granted.']
    ];
  }
  
  protected function getOptions()
  {
    return [
      ['user', 'u', InputOption::VALUE_OPTIONAL, 'Revoke a permission from a user.', null],
      ['group', 'g', InputOption::VALUE_OPTIONAL, 'Revoke a permission from a group.', null],
    ];
  }

}