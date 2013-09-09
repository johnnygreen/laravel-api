<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\UserInterface;

class TokenUserProvider extends EloquentUserProvider implements UserProviderInterface {

  public function retrieveByCredentials(array $credentials)
  {
    if (isset($credentials['access_token']))
    {
      $token = Token::where('access_token', '=', $credentials['access_token'])
                    ->valid()
                    ->first();

      if ( ! $token) return null;
      return $user = $token->user() ?: null;
    }

    return null;
  }

  public function validateCredentials(UserInterface $user, array $credentials)
  {
    if (isset($credentials['access_token']))
    {
      $token = Token::where('access_token', '=', $credentials['access_token'])
                    ->forUser($user)
                    ->first();

      // refresh the token
      $token->touch();

      return $token->isValid();
    }

    return false;
  }

}