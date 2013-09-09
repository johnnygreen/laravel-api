<?php namespace Johnnygreen\LaravelApi;

use Illuminate\Validation\Validator;

// helper trait for easy RESTful responses
trait RestfulJsonApi {
  // 200 OK
  //
  // Used in:
  // GET    index
  // GET    show
  // PUT    update
  // DELETE destroy
  //
  // In GET the list of resources / resource is returned
  // In PUT the updated resource is returned
  // In DELETE nothing is returned
  public function okay($resources = null)
  {
    return \Response::json($resources, 200);
  }

  // 201 Created
  //
  // Used in:
  // POST create
  //
  // Returns the resource on successful resource creation
  public function created($resource)
  {
    return \Response::json($resource, 201);
  }

  // 400 Bad Request
  //
  // Used in:
  // POST create
  // PUT  update
  //
  // When validation fails
  public function badRequest(Validator $validator)
  {
    return $this->error([
      'code'    => 400,
      'message' => 'Bad Request',
      'validations'  => $validator->messages()->toArray()
    ]);
  }

  // 401 Unauthorized
  //
  // Used when an api endpoint requires
  // authentication, but the user has
  // not "logged in" yet by receiving
  // an oauth token.
  public function unauthorized()
  {
    return $this->error([
      'code'    => 401,
      'message' => 'Unauthorized'
    ]);
  }

  // 404 Not Found
  //
  // Used in:
  // GET    show
  // PUT    update
  // DELETE destory
  //
  // Resource did not exist in the
  // database or route does not exist.
  public function notFound()
  {
    return $this->error([
      'code'    => 404,
      'message' => 'Not Found'
    ]);
  }

  // 405 Method Not Supported
  //
  // Use this method to fill all methods
  // that are either not supported or not
  // yet finished.
  public function methodNotAllowed()
  {
    return $this->error([
      'code'    => 405,
      'message' => 'Method Not Allowed'
    ]);
  }

  // 500 Internal Server Error
  //
  // Used in any method when a complex
  // process fails or is an exception
  // that is caught.
  public function internalServerError($message = "Internal Server Error")
  {
    \Log::error($message);
    
    return $this->error([
      'code'    => 500,
      'message' => $message,
    ]);
  }

  // 503 Server Unavailable
  //
  // Not sure if we need this one
  // if the server were unavailable
  // the load balancer would be the one
  // throwing this error.
  public function serverUnavailable()
  {
    return $this->error([
      'code'    => 503,
      'message' => 'Server Unavailable'
    ]);
  }

  // General Error Helper
  //
  // This method is used by all error
  // responses to simplify and unify
  // all error responses.
  public function error($options)
  {
    return Serializers\Error::json($options);
  }

  // This will catch all undefined methods
  // and return with a methodNotAllowed
  // response.
  public function __call($name, $arguments)
  {
    return $this->methodNotAllowed();
  }
}