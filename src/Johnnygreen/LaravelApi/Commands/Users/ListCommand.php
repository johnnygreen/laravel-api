<?php namespace Johnnygreen\LaravelApi\Commands\Users;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

use Johnnygreen\LaravelApi\Auth\User;

class ListCommand extends Command {

  protected $name = 'user:list';
  protected $description = 'List users.';

  public function fire()
  {
    $this->table = $this->getHelperSet()->get('table');
    $users = User::orderBy('username')->get();

    if ($users->isEmpty())
    {
      $this->comment('There are no users.');
    }
    else
    {
      $this->displayUsers($users);
    }
  }

  protected function displayUsers($users)
  {
    $headers = array('Username', 'Name');

    if ($this->option('with-groups'))
    {
      $headers[] = 'Groups';
      $users->load('groups');
    }

    $filtered = $this->filterUsers($users);

    $this->table->setHeaders($headers)->setRows($filtered);
    $this->table->render($this->getOutput());
  }

  protected function filterUsers($users)
  {
    $results = array();

    foreach($users as $user)
    {
      $result = [
        $user->username,
        "{$user->first_name} {$user->last_name}"
      ];

      if ($this->option('with-groups'))
      {
        $groups = $user->groups;
        $names = $groups->map(function($group) { return $group->name; });
        $result[] = $groups->isEmpty() ? "" : implode(' ', $names->toArray());
      }

      $results[] = $result;
    }

    return $results;
  }


  protected function getOptions()
  {
    return [
      ['with-groups', null, InputOption::VALUE_NONE, 'Also display groups of each user.'],
    ];
  }

}