<?php

\Route::filter(
	'LaravelApi.auth',
	'\Johnnygreen\LaravelApi\Auth\Filter'
);

\Route::resource(
	'api/tokens',
	'\Johnnygreen\LaravelApi\TokensController',
	['only' => ['store']]
);
