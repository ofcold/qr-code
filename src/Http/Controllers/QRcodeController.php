<?php

namespace Ofcold\QrCode\Http\Controllers;

use BaconQrCode\Renderer\Color\Rgb;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Ofcold\QrCode\BaconQrCodeGenerator;
use Ofcold\QrCode\HexToRgb;

/**
 * class QRcodeController
 *
 * PHP business application development core system
 *
 * This content is released under the Business System Toll License (MST)
 *
 * @link	 https://ofcold.com
 * @link	 https://naiveable.com
 *
 * @author   Bill Li (bill.li@ofcold.com) [Owner]
 *
 * @license https://licenses.naiveable.com/mst  MST License
 *
 * @copyright  Copyright (c) 2017-2019 Bill Li, Ofcold Institute of Technology. All rights reserved.
 */
class QRcodeController extends Controller
{
	/**
	 * Created QrCode.
	 *
	 * @param  BaconQrCodeGenerator $QRcode
	 * @param  string               $content
	 * @param  int|integer          $size
	 *
	 * @return Naiveable\Route\FactoryResponse
	 */
	public function make(BaconQrCodeGenerator $QRcode, string $content, int $size = 320)
	{
		$qrResponse = $QRcode->size($size);

		// Parameter analysis.
		$parameters = $this->parameters();

		// QR code output format, only supports png, eps, svg.
		if (isset($parameters['format'])) {
			$qrResponse->format($parameters['format']);
		}

		$qrResponse->module('roundness');

		// Set the color and background color..
		$this->color($qrResponse, $parameters);
		$this->color($qrResponse, $parameters, true);

		// In response to the QR code, the output source is from the QR code type output type.
		return response(
				// QR code type. the type support btc, email, geo, phone number, sms, wifi
				$qrResponse->{$this->responseMethod($parameters)}($content)
			)
			->header('Content-Type', $qrResponse->getContentType());
	}

	/**
	 * @param  BaconQrCodeGenerator $qrCode
	 * @param  array                $parameters
	 * @param  bool|boolean         $isBg
	 *
	 * @return void
	 */
	protected function color(BaconQrCodeGenerator $qrCode, array $parameters, bool $isBg = false): void
	{
		$field = 'color';
		$methd = 'color';

		if ($isBg) {
			$field = 'bg_color';
			$methd = 'backgroundColor';
		}

		if (isset($parameters[$field])) {
			$qrCode->color(HexToRgb::make($parameters[$field]));
		}
	}

	/**
	 * @param  BaconQrCodeGenerator $qrCode
	 * @param  array                $parameters
	 *
	 * @return void
	 */
	protected function logo(BaconQrCodeGenerator $qrCode, array $parameters)
	{
		if (! isset($parameters['logo'])) {
			return;
		}

		$logo = $parameters['logo'];
		$size = $parameters['logo_size'] ?? .2;
	}

	public function responseMethod(array $parameters)
	{
		if (isset($parameters['data_type'])) {
			return Arr::first(array_filter(
				['btc', 'email', 'geo', 'phoneNumber', 'sms', 'wifi'],
				function($t) use ($parameters) {
					return $t === $parameters['data_type'];
				}
			));
		}

		return 'generate';
	}

	protected function parameters()
	{
		return array_filter($this->request->all(), function($field) {
			return in_array($field, ['logo', 'format', 'logo_size', 'color', 'bg_color', 'data_type', 'use_inc']);
		}, ARRAY_FILTER_USE_KEY);
	}
}
