<?php namespace Johnnygreen\LaravelApi\Controllers;

use Johnnygreen\LaravelApi\Auth\Token;
use Johnnygreen\LaravelApi\RestfulJsonApi;

class TokensController extends BaseController {

  use RestfulJsonApi;

  public function store()
  {
    $input = \Input::get('credentials') ?: [];

    $validator = \Validator::make($input, [
      'username' => ['required_without:customers_email_address'],
      'customers_email_address' => ['required_without:username'],
      'password' => ['required']
    ]);

    if ($validator->passes())
    {
      if (\Auth::once($input))
      {
        try
        {
          $token = Token::renewOrCreate(\Auth::user());
        }
        catch (\Exception $e)
        {
          return $this->internalServerError($e->getMessage());
        }

        return $this->created($token);
      }
      else
      {
        return $this->unauthorized();
      }
    }
    else
    {
      return $this->badRequest($validator);
    }
  }

}
