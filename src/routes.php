<?php

use Illuminate\Routing\Router;

\Route::filter(
	\Config::get('laravel-api::filter.name'),
	'\Johnnygreen\LaravelApi\Auth\Filter'
);

\Route::resource(
	\Config::get('laravel-api::routes.tokens'),
	'\Johnnygreen\LaravelApi\TokensController',
	['only' => ['store']]
);
