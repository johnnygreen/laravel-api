<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Hashing\HasherInterface;

class PassthruHasher implements HasherInterface {

  public function make($value, array $options = array())
  {
    return $value;
  }

  public function check($value, $hashedValue, array $options = array())
  {
    return $value === $hashedValue;
  }

  public function needsRehash($hashedValue, array $options = array())
  {
    return false;
  }

}