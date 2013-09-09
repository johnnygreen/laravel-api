<?php

Route::resource('tokens', '\Johnnygreen\LaravelApi\Controllers\TokensController', [
	'only' => ['store']
]);
