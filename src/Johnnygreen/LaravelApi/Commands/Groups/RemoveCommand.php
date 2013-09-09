<?php namespace Johnnygreen\LaravelApi\Commands\Groups;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Johnnygreen\LaravelApi\Auth\Group;

class RemoveCommand extends Command {

  protected $name = 'group:remove';
  protected $description = 'Remove group.';

  public function fire()
  {
    $name = $this->argument('name');

    $group = Group::where('name', '=', $name)->first();

    if ( ! is_null($group))
    {
      try
      {
        $group->users()->delete();
        $group->permissions()->detach();
        $group->delete();
      }
      catch(\Exception $e)
      {
        $this->error($e->getMessage());
      }

      $this->info("{$name} group removed.");
    }
    else
    {
      $this->comment("{$name} group does not exist.");
    }

  }

  protected function getArguments()
  {
    return array(
      ['name', InputArgument::REQUIRED, 'The name of the group being removed.'],
    );
  }

}
