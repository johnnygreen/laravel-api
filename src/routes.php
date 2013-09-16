<?php

$filter_name  = \Config::get('johnnygreen/laravel-api::filter.name') ?: 'LaravelApi.auth';
$tokens_route = \Config::get('johnnygreen/laravel-api::routes.tokens') ?: 'tokens';

echo "<pre>" . print_r($filter_name, true) . "</pre>";
exit;

\Route::filter(
	$filter_name,
	'\Johnnygreen\LaravelApi\Auth\Filter'
);

\Route::resource(
	$tokens_route,
	'\Johnnygreen\LaravelApi\TokensController',
	['only' => ['store']]
);
