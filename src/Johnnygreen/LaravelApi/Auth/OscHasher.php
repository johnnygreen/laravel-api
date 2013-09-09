<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Hashing\HasherInterface;

class OscHasher implements HasherInterface {

  public function make($value, array $options = array())
  {
    $password = '';

    for ($i = 0; $i < 10; $i++)
    {
      $password .= mt_rand();
    }

    $salt = substr(md5($password), 0, 2);
    $password = md5($salt . $value) . ':' . $salt;

    return $password;
  }

  public function check($value, $hashedValue, array $options = array())
  {
    if ( ! is_null($value) && ! is_null($hashedValue))
    {
      $stack = explode(':', $hashedValue);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1].$value) == $stack[0])
      {
        return true;
      }
    }

    return false;
  }

  public function needsRehash($hashedValue, array $options = array())
  {
    return false;
  }

}
