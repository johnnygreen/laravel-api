<?php namespace Johnnygreen\LaravelApi\Commands\Permissions;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Johnnygreen\LaravelApi\Auth\Permission;
use Johnnygreen\LaravelApi\Auth\User;
use Johnnygreen\LaravelApi\Auth\Group;

class GrantCommand extends Command {

  protected $name = 'permission:grant';
  protected $description = 'Grant a permission to a User.';

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

      $already_granted = $permission->users()->where('username', '=', $username)->first();

      if (is_null($already_granted))
      {
        try
        {
          $permission->user()->attach($user);
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }

        $this->info("{$name} granted to {$username}.");
      }
      else
      {
        $this->comment("{$name} is already granted to {$username}.");
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

      $already_granted = $permission->groups()->where('name', '=', $groupname)->first();

      if (is_null($already_granted))
      {
        try
        {
          $permission->groups()->attach($group);
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }

        $this->info("{$name} granted to {$groupname}.");
      }
      else
      {
        $this->comment("{$name} is already granted to {$groupname}.");
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
      ['user', 'u', InputOption::VALUE_OPTIONAL, 'Grant a user a permission.', null],
      ['group', 'g', InputOption::VALUE_OPTIONAL, 'Grant a group a permissions.', null],
    ];
  }

}
