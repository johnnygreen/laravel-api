<?php namespace Johnnygreen\LaravelApi\Auth;

use Illuminate\Auth\UserInterface;

class Token extends \Eloquent implements UserInterface {

  // in seconds
  public $timeout = [
    'created_at' => 86400, // 1 day
    'updated_at' => 1800   // 30 minutes
  ];

  public function getAuthIdentifier()
  {
    return $this->getKey();
  }

  public function getAuthPassword()
  {
    return $this->access_token;
  }

  public function getRememberToken() { return ''; }

  public function setRememberToken($token) {}

  public function getRememberTokenName() { return 'remember_token'; }

  public function user()
  {
    $model = $this->user_model;

    return $model::find($this->user_id);
  }

  public function isValid()
  {
    return strtotime($this->updated_at) > time() - $this->timeout['updated_at']
       and strtotime($this->created_at) > time() - $this->timeout['created_at'];
  }

  public function scopeForUser($query, UserInterface $user)
  {
    return $query->where('user_id', '=', $user->getAuthIdentifier())
                 ->where('user_model', '=', get_class($user));
  }

  public function scopeValid($query)
  {
    return $query->where('updated_at', '>', date("Y-m-d H:i:s", time() - $this->timeout['updated_at']))
                 ->where('created_at', '>', date("Y-m-d H:i:s", time() - $this->timeout['created_at']));
  }

  public static function extractFromHeader()
  {
    $access_token = "";
    $authorization = \Request::header('Authorization');

    if (is_null($authorization) and function_exists('apache_request_headers'))
    {
      $headers = apache_request_headers();
      $authorization = isset($headers['Authorization'])
                     ? $headers['Authorization']
                     : null;
    }

    if ( ! is_null($authorization))
    {
      $access_token = str_replace('Bearer ', '', $authorization);
    }

    return $access_token;
  }

  public static function renewOrCreate(UserInterface $user)
  {
    $token = self::forUser($user)->valid()->first();

    if ( ! $token)
    {
      $token = new self;
      $token->user_id      = $user->getAuthIdentifier();
      $token->user_model   = get_class($user);
      $token->access_token = self::uuid();
      $token->save();
    }
    else
    {
      $token->touch();
    }

    return $token;
  }

  public static function uuid()
  {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }

}
