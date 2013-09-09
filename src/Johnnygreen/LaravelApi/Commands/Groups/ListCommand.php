<?php namespace Johnnygreen\LaravelApi\Commands\Groups;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Johnnygreen\LaravelApi\Auth\Group;

class ListCommand extends Command {

  protected $name = 'group:list';
  protected $description = 'List groups.';

  public function fire()
  {
    $this->comment('Groups:');

    $groups = Group::all();

    if ($groups->isEmpty())
    {
      $this->comment('There are no groups.');
      exit;
    }

    foreach($groups as $group)
    {
      $message = '';

      $users = $group->users;

      if ($this->option('with-users'))
      {
        if($users->isEmpty())
        {
          $message .= ' No users.';
        }
        else
        {
          foreach($users as $user)
          {
            $message .= " {$user->username}";
          }
        }
      }

      $this->info("{$group->name}<comment>{$message}</comment>");
    }
  }

  protected function getOptions()
  {
    return [
      ['with-users', null, InputOption::VALUE_NONE, 'Also display users of each group.'],
    ];
  }

}