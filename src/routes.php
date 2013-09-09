<?php

\Route::filter('LaravelApi.auth', '\Johnnygreen\LaravelApi\Auth\Filter');

\Route::resource('tokens', '\Johnnygreen\LaravelApi\Controllers\TokensController', [
	'only' => ['store']
]);
