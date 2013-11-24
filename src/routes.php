<?php

\Route::post(
  $app['config']->get('laravel-api::tokens_route', 'tokens'),
  '\Johnnygreen\LaravelApi\TokensController@store'
);
