<?php

\Route::filter(
	$app['config']->get('laravel-api::filter_name', 'LaravelApi.auth'),
	'\Johnnygreen\LaravelApi\Auth\Filter'
);
