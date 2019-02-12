<?php

namespace Ofcold\QrCode\Contracts;

interface QrCodeInterface
{
	/**
	 * Generates a QrCode.
	 *
	 * @param string	  $text	 The text to be converted into a QrCode
	 * @param null|string $filename The filename and path to save the QrCode file
	 *
	 * @return string|void Returns a QrCode string depending on the format, or saves to a file.
	 */
	public function generate($text, $filename = null);

	/**
	 * Switches the format of the outputted QrCode or defaults to SVG.
	 *
	 * @param string $format
	 *
	 * @return $this
	 */
	public function format(string $format = 'svg');

	/**
	 * Changes the size of the QrCode.
	 *
	 * @param int $pixels The size of the QrCode in pixels
	 *
	 * @return $this
	 */
	public function size($pixels);

	/**
	 * Changes the foreground color of a QrCode.
	 *
	 * @param array $color
	 *
	 * @return $this
	 */
	public function color(array $color);

	/**
	 * Changes the background color of a QrCode.
	 *
	 * @param array $color
	 *
	 * @return $this
	 */
	public function backgroundColor(array $color);

	/**
	 * Changes the error correction level of a QrCode.
	 *
	 * @param int $level Desired error correction level.  L = 1 M = 0 Q = 3 H = 1
	 *
	 * @return $this
	 */
	public function errorCorrection(int $level);

	/**
	 * Creates a margin around the QrCode.
	 *
	 * @param int $margin The desired margin in pixels around the QrCode
	 *
	 * @return $this
	 */
	public function margin($margin);

	/**
	 * Sets the Encoding mode.
	 *
	 * @param string $encoding
	 *
	 * @return $this
	 */
	public function encoding($encoding);

	/**
	 * Merges an image with the center of the QrCode.
	 *
	 * @param $image string The filepath to an image
	 * @param $percentage float The percentage that the merge image should take up.
	 *
	 * @return $this
	 */
	public function merge($image, $percentage = .2);
}
