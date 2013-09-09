<?php namespace Johnnygreen\LaravelApi\Auth;

use Johnnygreen\LaravelApi\Serializers\Error;

class Filter {

    public function filter()
    {
      if ( ! \Auth::check() or \Auth::guest())
      {
        return Error::json([
          'code'    => 401,
          'message' => 'Unauthorized'
        ]);
      }
    }

}
