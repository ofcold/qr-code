<?php

namespace Ofcold\QrCode;

use Illuminate\Support\Str;

class HexToRgb
{
	/**
	 * [make description]
	 *
	 * @param  string $hex
	 *
	 * @return array
	 */
	public static function make(string $hex)
	{
		if (Str::is('*.*.*', $hex)) {
			return explode('.', $hex);
		}

		$hex = str_replace('#', '', $hex);

		if(strlen($hex) === 3) {
			return [
				hexdec(substr($hex, 0, 1) . substr($hex, 0, 1)),
				hexdec(substr($hex, 1, 1) . substr($hex, 1, 1)),
				hexdec(substr($hex, 2, 1) . substr($hex, 2, 1))
			];
		}

		return [
			hexdec(substr($hex, 0, 2)),
			hexdec(substr($hex, 2, 2)),
			hexdec(substr($hex, 4, 2)),
		];
	}
}
