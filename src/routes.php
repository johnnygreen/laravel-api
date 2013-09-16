<?php

\Route::filter(
	$app['config']->get('laravel-api::filter_name', 'LaravelApi.auth'),
	'\Johnnygreen\LaravelApi\Auth\Filter'
);

\Route::resource(
	$app['config']->get('laravel-api::tokens_route', 'tokens'),
	'\Johnnygreen\LaravelApi\TokensController',
	['only' => ['store']]
);
