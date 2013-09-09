<?php

Route::filter(
	Config::get('johnnygreen/laravel-api::filter.name'),
	'\Johnnygreen\LaravelApi\Auth\Filter'
);

Route::resource(
	Config::get('johnnygreen/laravel-api::routes.tokens'),
	'\Johnnygreen\LaravelApi\Controllers\TokensController',
	['only' => ['store']]
);
