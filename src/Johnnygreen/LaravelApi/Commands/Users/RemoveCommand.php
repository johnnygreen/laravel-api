<?php namespace Johnnygreen\LaravelApi\Commands\Users;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use Johnnygreen\LaravelApi\Auth\User;

class RemoveCommand extends Command {

  protected $name = 'user:remove';
  protected $description = 'Remove user.';

  public function fire()
  {
    $username = $this->argument('username');

    $user = User::where('username', '=', $username)->first();

    if ( ! is_null($user))
    {
      try
      {
        $user->groups()->detach();
        $user->permissions()->detach();
        $user->delete();
      }
      catch(\Exception $e)
      {
        $this->error($e->getMessage());
      }

      $this->info("{$username} user removed.");
    }
    else
    {
      $this->comment("{$username} user does not exist.");
    }

  }

  protected function getArguments()
  {
    return array(
      ['username', InputArgument::REQUIRED, 'The name of the user being removed.'],
    );
  }

}
