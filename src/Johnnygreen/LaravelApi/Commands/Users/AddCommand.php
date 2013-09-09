<?php namespace Johnnygreen\LaravelApi\Commands\Users;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use Johnnygreen\LaravelApi\Auth\User;
use Johnnygreen\LaravelApi\Auth\Md5Hasher;

class AddCommand extends Command {

  protected $name = 'user:add';
  protected $description = 'Add user.';

  public function fire()
  {
    $username = $this->argument('username');

    $user = User::where('username', '=', $username)->first();

    if (is_null($user))
    {
      $user = new User;
      $user->username = $username;
      $user->type = $this->ask('Type [Admin|Sales Clerk]:');
      $user->first_name = $this->ask('First Name:');
      $user->last_name = $this->ask('Last Name:');
      $hasher = new Md5Hasher;
      $user->password = $hasher->make($this->ask('Password:'));

      if ($this->confirm('Is the information correct? [yes|no]'))
      {
        try
        {
          $user->save();
        }
        catch(\Exception $e)
        {
          $this->error($e->getMessage());
        }

        $this->info("{$username} user added.");
      }
      else
      {

      }
    }
    else
    {
      $this->comment("{$username} user exists.");
    }

  }

  protected function getArguments()
  {
    return array(
      ['username', InputArgument::REQUIRED, 'The username of the user being added.'],
    );
  }

}