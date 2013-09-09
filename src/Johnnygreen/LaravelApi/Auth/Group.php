<?php namespace Johnnygreen\LaravelApi\Auth;

class Group extends \Eloquent {

  public function users()
  {
    return $this->belongsToMany('\Johnnygreen\LaravelApi\Auth\User');
  }

  public function permissions()
  {
    return $this->belongsToMany('\Johnnygreen\LaravelApi\Auth\Permission');
  }

}
