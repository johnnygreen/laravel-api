<?php namespace Johnnygreen\LaravelApi\Commands\Groups;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

use Johnnygreen\LaravelApi\Auth\Group;

class AddCommand extends Command {

  protected $name = 'group:add';
  protected $description = 'Add group.';

  public function fire()
  {
    $name = $this->argument('name');

    $group = Group::where('name', '=', $name)->first();

    if (is_null($group))
    {
      $group = new Group;
      $group->name = $name;

      try
      {
        $group->save();
      }
      catch(\Exception $e)
      {
        $this->error($e->getMessage());
      }

      $this->info("{$name} group added.");
    }
    else
    {
      $this->comment("{$name} group exists.");
    }

  }

  protected function getArguments()
  {
    return array(
      ['name', InputArgument::REQUIRED, 'The name of the group being added.'],
    );
  }

}