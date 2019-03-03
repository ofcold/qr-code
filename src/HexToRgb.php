<?php

namespace Ofcold\QrCode;

use Illuminate\Support\Str;

class HexToRgb
{
	/**
	 * [make description]
	 *
	 * @param	string $hex
	 *
	 * @return array
	 */
	public static function make(string $hex)
	{
		if (static::isRGB($hex)) {
			return array_map(function($val) {
				return (int) trim($val);
			}, explode(',', $hex));
		}

		if (! static::isHex($hex)) {
			return [0, 0, 0];
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

	/**
	 * Check if the color value is hexadecimal.
	 *
	 * @param  string $hex
	 *
	 * @return boolean
	 */
	public static function isHex(string $hex): bool
	{
		return (bool) preg_match('/^[#]*([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i', '#'.$hex);
	}

	/**
	 * Check if the color value is rgb
	 *
	 * @param  string  $rgb
	 *
	 * @return boolean
	 */
	public static function isRGB(string $rgb): bool
	{
		return (bool) preg_match(
			'/([R][G][B][A]?[(]\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])\s*,\s*([01]?[0-9]?[0-9]|2[0-4][0-9]|25[0-5])(\s*,\s*((0\.[0-9]{1})|(1\.0)|(1)))?[)])/i',
			'RGB('.$rgb.')'
		);
	}
}
