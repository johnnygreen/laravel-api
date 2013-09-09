<?php namespace Johnnygreen\LaravelApi\Commands\Groups;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Johnnygreen\LaravelApi\Auth\User;
use Johnnygreen\LaravelApi\Auth\Group;

class JoinCommand extends Command {

  protected $name = 'group:join';
  protected $description = 'Add a User to a Group.';

  public function fire()
  {
    $name = $this->argument('name');
    $group = Group::where('name', '=', $name)->first();

    if (is_null($group))
    {
      $this->comment("{$name} is not a valid group.");
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

      $already_joined = $user->groups()->where('name', '=', $name)->first();

      if (is_null($already_joined))
      {
        try
        {
          $user->groups()->attach($group);
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }

        $this->info("{$username} added to {$name}.");
      }
      else
      {
        $this->comment("{$username} is already a member of {$name}.");
      }

      return;
    }

    $this->comment('Please use the user option.');
  }

  protected function getArguments()
  {
    return [
      ['name', InputArgument::REQUIRED, 'The name of the group being joined.']
    ];
  }

  protected function getOptions()
  {
    return [
      ['user', 'u', InputOption::VALUE_OPTIONAL, 'Add a user to a group.', null]
    ];
  }

}