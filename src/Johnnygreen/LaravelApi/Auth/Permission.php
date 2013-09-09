<?php namespace Johnnygreen\LaravelApi\Auth;

class Permission extends \Eloquent {

  public function users()
  {
    return $this->belongsToMany('\Johnnygreen\LaravelApi\Auth\User');
  }

  public function groups()
  {
    return $this->belongsToMany('\Johnnygreen\LaravelApi\Auth\Group');
  }

}
