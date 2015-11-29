<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BladeExtensions;

class LibServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
        BladeExtensions::register();

		// $this->app->singleton('db', function(PDO $pdo)
		// {
		//     return new \App\Models\Connection($pdo);
		// });
        
		$this->app->singleton('lib', function()
		{
		    return new \App\Services\Lib;
		});

		$this->app->singleton('trace', function()
		{
		    return new \App\Services\Trace;
		});
		
		$this->app->singleton('farsi', function()
		{
		    return new \App\Services\Farsi;
		});

		$this->app->singleton('encode', function()
		{
		    return new \App\Services\Encode;
		});

		$this->app->singleton('query', function()
		{
		    return new \App\Services\Query;
		});

		$this->app->singleton('helper', function()
		{
		    return new \App\Services\Helpers;
		});
	}

}
