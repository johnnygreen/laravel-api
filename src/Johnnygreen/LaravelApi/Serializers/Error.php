<?php namespace Johnnygreen\LaravelApi\Serializers;

class Error {

  public function __construct($options)
  {
    $this->error = [
      'code'    => $options['code'],
      'message' => $options['message']
    ];
      
    if (array_key_exists('validations', $options))
    {
      $this->error['validations'] = $options['validations'];
    }
  }
  
  public static function json($options)
  {
    return \Response::json(new self($options), $options['code']);
  }
  
}