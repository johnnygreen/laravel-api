<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Hashing\HasherInterface;

class Md5Hasher implements HasherInterface {

  public function make($value, array $options = array())
  {
    return md5($value);
  }

  public function check($value, $hashedValue, array $options = array())
  {
    return md5($value) === $hashedValue;
  }

  public function needsRehash($hashedValue, array $options = array())
  {
    return false;
  }

}