<?php namespace Johnnygreen\LaravelApi\Commands\Permissions;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Johnnygreen\LaravelApi\Auth\Permission;
use Johnnygreen\LaravelApi\Auth\User;
use Johnnygreen\LaravelApi\Auth\Group;

class ListCommand extends Command {

  protected $name = 'permission:list';
  protected $description = 'List permissions.';

  public function fire()
  {
    if ($username = $this->option('user'))
    {
      $user = User::where('username', '=', $username)->first();

      if (is_null($user))
      {
        $this->comment("{$username} is not a valid user.");
        exit;
      }

      $this->comment("{$username} user permissions:");

      $permissions = $user->permissions()->orderBy('name')->get();

      if ($permissions->isEmpty())
      {
        $this->comment("{$username} has no user permissions.");
      }
      else
      {
        foreach($permissions as $permission)
        {
          $this->info($permission->name);
        }
      }

      if ($user->groups->isEmpty())
      {
        $this->comment("{$username} has no group permissions.");
      }
      else
      {
        foreach($user->groups as $group)
        {
          foreach($group->permissions as $permission)
          {
            $this->info("{$permission->name} <comment>({$group->name})</comment>");
          }
        }
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

      $this->comment("{$groupname} group permissions:");

      $permissions = $group->permissions()->orderBy('name')->get();

      if ($permissions->isEmpty())
      {
        $this->comment("{$groupname} has no permissions.");
      }
      else
      {
        foreach($permissions as $permission)
        {
          $this->info($permission->name);
        }
      }

      return;
    }

    $this->comment('All system permissions:');
    $permissions = Permission::orderBy('name')->get();

    if ($permissions->isEmpty())
    {
      $this->comment('There are no system permissions.');
    }
    else
    {
      foreach($permissions as $permission)
      {
        $this->info($permission->name);
      }
    }
  }

  protected function getOptions()
  {
    return [
      ['user', 'u', InputOption::VALUE_OPTIONAL, 'Check a user\'s permissions.', null],
      ['group', 'g', InputOption::VALUE_OPTIONAL, 'Check a group\'s permissions.', null],
    ];
  }

}