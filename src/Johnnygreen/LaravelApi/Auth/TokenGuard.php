<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Auth\Guard;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\UserInterface;

// https://github.com/illuminate/auth/blob/master/Guard.php
class TokenGuard extends Guard {

  public $valid_username_fields = array(
    'customers_email_address',
    'username',
    'access_token'
  );

  public function __construct()
  {
    // should not need any session info
    // trying to override every function that
    // refers to $this->session
    $this->session = \App::make('session');
  }

  // digs thru the credentials and returns the
  // first valid username field that it finds
  public function getValidUsernameField(array $credentials = array())
  {
    $fields = array_keys($credentials);
    $common = array_intersect($this->valid_username_fields, $fields);
    return reset($common);
  }

  public function attempt(array $credentials = array(), $remember = false, $login = true)
  {
    if ($username = $this->getValidUsernameField($credentials))
    {
      switch ($username)
      {
        case 'customers_email_address':
          $provider = new EloquentUserProvider(new OscHasher, 'Johnnygreen\LaravelApi\Auth\Customer');
          break;

        case 'username':
          $provider = new EloquentUserProvider(new Md5Hasher, 'Johnnygreen\LaravelApi\Auth\User');
          break;

        case 'access_token':
          $provider = new TokenUserProvider(new PassthruHasher, 'Johnnygreen\LaravelApi\Auth\Token');
          break;

        default:
          return false;
      }

      $this->setProvider($provider);
      return parent::attempt($credentials, $remember, $login);
    }

    return false;
  }

  public function login(UserInterface $user, $remember = false)
  {
    if (isset($this->events))
    {
      $this->events->fire('auth.login', array($user, $remember));
    }

    $this->setUser($user);
  }

  public function check($name = null)
  {
    $user = $this->user();
    $name = $name ?: \Route::currentRouteAction();
    
    if (is_null($user)) return false;
    if ( ! method_exists($user, 'hasPermissionTo')) return false;
    if ( ! $user->hasPermissionTo($name)) return false;
    return true;
  }

}
