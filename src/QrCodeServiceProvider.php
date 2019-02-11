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
