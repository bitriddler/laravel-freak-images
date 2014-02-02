<?php namespace Kareem3d\FreakImages;

use Illuminate\Support\ServiceProvider;
use Kareem3d\Freak\Core\Package;

class FreakImagesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('kareem3d/freak-images');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $package = new Package('images', $this->app->make('Kareem3d\FreakImages\FreakImagesPackageController'));

        $this->app->make('Kareem3d\Freak\Freak')->addPackage($package);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}