<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Auth\UserInterface;

class User extends \Eloquent implements UserInterface {

  public function getAuthIdentifier()
  {
    return $this->getKey();
  }

  public function getAuthPassword()
  {
    return $this->password;
  }

  public function permissions()
  {
    return $this->belongsToMany('\Johnnygreen\LaravelApi\Auth\Permission');
  }

  public function groups()
  {
    return $this->belongsToMany('\Johnnygreen\LaravelApi\Auth\Group');
  }

  public function hasPermissionTo($name)
  {
    $user_has_permission = false;
    $group_has_permission = false;

    if ( ! $user_has_permission = $this->permissions()->where('name', '=', $name)->first())
    {
      foreach($this->groups as $group)
      {
        if ($group_has_permission = $group->permissions()->where('name', '=', $name)->first())
          break;
      }
    }

    return $user_has_permission or $group_has_permission;
  }

}
