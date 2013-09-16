<?php namespace Johnnygreen\LaravelApi;

use Illuminate\Support\ServiceProvider;

use Johnnygreen\LaravelApi\Auth;
use Johnnygreen\LaravelApi\Commands;

class LaravelApiServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot()
	{
		$this->package('johnnygreen/laravel-api');

		require __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['laravel-api'] = $this->app->share(function($app)
		{
			return new LaravelApi;
		});

		$this->registerExtensions();
		$this->registerErrorHandlers();
		$this->registerApiCommands();
		$this->registerGroupCommands();
		$this->registerUserCommands();
		$this->registerPermissionCommands();
	}

	public function registerExtensions()
	{
		$this->app->booted(function($app)
		{
			\Auth::extend('token', function()
			{
				return new Auth\TokenGuard;
			});
		});
	}

	public function registerErrorHandlers()
	{
		App::error(function(MethodNotAllowedHttpException $exception, $code)
		{
			if (\Request::getMethod() === "OPTIONS")
			{
				$headers = $exception->getHeaders();
				$allow = isset($headers['Allow']) ? $headers['Allow'] : '*';

				$headers = [
					'Access-Control-Allow-Origin' => '*',
					'Access-Control-Allow-Methods'=> $allow,
					'Access-Control-Allow-Headers'=> 'X-Requested-With, content-type'
				];

				return Response::make('', 200, $headers);
			}

			return \v1\Serializer\Error::json([
				'code'    => $code,
				'message' => 'Method Not Allowed'
			]);
		});
	}

	public function registerApiCommands()
	{
		$this->app['command.api'] = $this->app->share(function($app)
		{
			return new Commands\ApiCommand;
		});

		$this->commands('command.api');
	}

	public function registerGroupCommands()
	{
		$this->app['command.group.add'] = $this->app->share(function($app)
		{
			return new Commands\Groups\AddCommand;
		});

		$this->app['command.group.join'] = $this->app->share(function($app)
		{
			return new Commands\Groups\JoinCommand;
		});

		$this->app['command.group.leave'] = $this->app->share(function($app)
		{
			return new Commands\Groups\LeaveCommand;
		});

		$this->app['command.group.list'] = $this->app->share(function($app)
		{
			return new Commands\Groups\ListCommand;
		});

		$this->app['command.group.remove'] = $this->app->share(function($app)
		{
			return new Commands\Groups\RemoveCommand;
		});

		$this->commands(
			'command.group.add',
			'command.group.join',
			'command.group.leave',
			'command.group.list',
			'command.group.remove'
		);
	}

	public function registerUserCommands()
	{
		$this->app['command.user.add'] = $this->app->share(function($app)
		{
			return new Commands\Users\AddCommand;
		});

		$this->app['command.user.list'] = $this->app->share(function($app)
		{
			return new Commands\Users\ListCommand;
		});

		$this->app['command.user.remove'] = $this->app->share(function($app)
		{
			return new Commands\Users\RemoveCommand;
		});

		$this->commands(
			'command.user.add',
			'command.user.list',
			'command.user.remove'
		);
	}

	public function registerPermissionCommands()
	{
		$this->app['command.permission.add'] = $this->app->share(function($app)
		{
			return new Commands\Permissions\AddCommand;
		});

		$this->app['command.permission.grant'] = $this->app->share(function($app)
		{
			return new Commands\Permissions\GrantCommand;
		});

		$this->app['command.permission.list'] = $this->app->share(function($app)
		{
			return new Commands\Permissions\ListCommand;
		});

		$this->app['command.permission.remove'] = $this->app->share(function($app)
		{
			return new Commands\Permissions\RemoveCommand;
		});

		$this->app['command.permission.revoke'] = $this->app->share(function($app)
		{
			return new Commands\Permissions\RevokeCommand;
		});

		$this->app['command.permission.seed'] = $this->app->share(function($app)
		{
			return new Commands\Permissions\SeedCommand;
		});

		$this->commands(
			'command.permission.add',
			'command.permission.grant',
			'command.permission.list',
			'command.permission.remove',
			'command.permission.revoke',
			'command.permission.seed'
		);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['laravel-api'];
	}

}
