<?php

namespace Ofcold\QrCode;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class QrCodeServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		$this->app->booted(function () {
			// Routes.
			$this->app->routesAreCached()
				? $this->loadCachedRoutes()
				: $this->loadRoutes();
		});
	}

	/**
	 * Load the cached routes for the application.
	 *
	 * @return void
	 */
	protected function loadCachedRoutes(): void
	{
		require $this->app->getCachedRoutesPath();
	}

	/**
	 * Load the application routes.
	 *
	 * @return void
	 */
	protected function loadRoutes(): void
	{
		Route::namespace('Ofcold\QrCode\Http\Controllers')
			->domain(env('QR_CODE_ROUTE_DOMAIN', null))
			->group(function () {
				Route::get('qrcode/{content}/{size?}', 'QRcodeController@make')
					->name('qrcode::generator');
			});
	}


	/**
	 * Register the service provider.
	 */
	public function register(): void
	{
		$this->app->bind('qrcode', function () {
			return new BaconQrCodeGenerator();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides(): array
	{
		return ['qrcode'];
	}
}
