<?php

use Illuminate\Routing\Router;

\Route::filter(
	\Config::get('johnnygreen/laravel-api::filter.name'),
	'\Johnnygreen\LaravelApi\Auth\Filter'
);

\Route::resource(
	\Config::get('johnnygreen/laravel-api::routes.tokens'),
	'\Johnnygreen\LaravelApi\TokensController',
	['only' => ['store']]
);
