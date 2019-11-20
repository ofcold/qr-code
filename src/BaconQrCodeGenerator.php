<?php

namespace Ofcold\QrCode;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\Color\Gray;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Eye\CompositeEye;
use BaconQrCode\Renderer\Eye\ModuleEye;
use BaconQrCode\Renderer\Eye\SimpleCircleEye;
use BaconQrCode\Renderer\Eye\SquareEye;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\RendererInterface;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\Module\DotsModule;
use BaconQrCode\Renderer\Module\RoundnessModule;
use BaconQrCode\Renderer\Module\SquareModule;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Ofcold\QrCode\Contracts\QrCodeInterface;
use Illuminate\Support\Str;

class BaconQrCodeGenerator implements QrCodeInterface
{
	/**
	 * The qr code size.
	 *
	 * @var integer
	 */
	protected $size = 320;

	/**
	 * The qr code margin.
	 *
	 * @var integer
	 */
	protected $margin = 0;

	/**
	 * Describing how modules should be rendered.
	 *
	 * @var \BaconQrCode\Renderer\Module\ModuleInterface
	 */
	protected $module;

	/**
	 * Back ends able to to produce path based images.
	 *
	 * @var \BaconQrCode\Renderer\Image\ImageBackEndInterface
	 */
	protected $format;

	/**
	 * Foreground color of a QrCode.
	 *
	 * @var \BaconQrCode\Renderer\Color\ColorInterface
	 */
	protected $foregroundColor;

	/**
	 * Background color of a QrCode.
	 *
	 * @var \BaconQrCode\Renderer\Color\ColorInterface
	 */
	protected $backgroundColor;

	/**
	 * QR code output type.
	 *
	 * @var string
	 */
	protected $contentType;

	/**
	 * Holds the QrCode error correction levels.  This is stored by using the BaconQrCode ErrorCorrectionLevel class constants.
	 *
	 * @var \BaconQrCode\Common\ErrorCorrectionLevel
	 */
	protected $errorCorrection;

	/**
	 * Holds the Encoder mode to encode a QrCode.
	 *
	 * @var string
	 */
	protected $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING;

	/**
	 * Holds an image string that will be merged with the QrCode.
	 *
	 * @var null|string
	 */
	protected $imageMerge = null;

	/**
	 * The percentage that a merged image should take over the source image.
	 *
	 * @var float
	 */
	protected $imagePercentage = .2;

	/**
	 * Generates a QrCode.
	 *
	 * @param string	  $text	 The text to be converted into a QrCode
	 * @param null|string $filename The filename and path to save the QrCode file
	 *
	 * @return string|void Returns a QrCode string depending on the format, or saves to a file.
	 */
	public function generate($text, $filename = null)
	{
		// Holds the BaconQrCode Writer Object.
		$writer = new Writer($this->imageRendererInstance());

		$qrCode = $writer->writeString($text, $this->encoding, $this->errorCorrection);

		if ($this->imageMerge !== null) {
			$merger = new ImageMerge(new Image($qrCode), new Image($this->imageMerge));
			$qrCode = $merger->merge($this->imagePercentage);
		}

		if ($filename === null) {
			return $qrCode;
		}

		return file_put_contents($filename, $qrCode);
	}

	/**
	 * Merges an image with the center of the QrCode.
	 *
	 * @param $filepath string The filepath to an image
	 * @param $percentage float The amount that the merged image should be placed over the qrcode.
	 * @param $absolute boolean Whether to use an absolute filepath or not.
	 *
	 * @return $this
	 */
	public function merge($filepath, $percentage = .2, $absolute = false)
	{
		if (!$absolute) {
			$this->imageMerge = file_get_contents(base_path($filepath));
		}

		$this->imageMerge = file_get_contents($filepath);

		$this->imagePercentage = $percentage;

		return $this;
	}

	/**
	 * Merges an image string with the center of the QrCode, does not check for correct format.
	 *
	 * @param $content string The string contents of an image.
	 * @param $percentage float The amount that the merged image should be placed over the qrcode.
	 *
	 * @return $this
	 */
	public function mergeString($content, $percentage = .2)
	{
		$this->imageMerge = $content;
		$this->imagePercentage = $percentage;

		return $this;
	}

	/**
	 * Switches the format of the outputted QrCode or defaults to SVG.
	 *
	 * @param string $format The desired format.
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return $this
	 */
	public function format(string $format = 'svg')
	{
		$this->format = $format;

		return $this;
	}

	/**
	 * Changes the size of the QrCode.
	 *
	 * @param int $pixels The size of the QrCode in pixels
	 *
	 * @return $this
	 */
	public function size($pixels)
	{
		$this->size = $pixels;

		return $this;
	}

	/**
	 * Changes the style of the QrCode.
	 *
	 * @param string|null $module The module of the QrCode
	 *
	 * @return $this
	 */
	public function module(?string $module = null)
	{
		switch ($module) {
			case 'dots':
					$this->module = new DotsModule(.4);
				break;

			case 'roundness':
					$this->module = new RoundnessModule(.8);
				break;

			default:
					$this->module = SquareModule::instance();
				break;
		}
		return $this;
	}

	/**
	 * Changes the foreground color of a QrCode.
	 *
	 * @param array $color
	 *
	 * @return $this
	 */
	public function color(array $color)
	{
		$this->foregroundColor = new Rgb(...$color);

		return $this;
	}

	/**
	 * Changes the background color of a QrCode.
	 *
	 * @param array $color
	 *
	 * @return $this
	 */
	public function backgroundColor(array $color)
	{
		$this->backgroundColor = new Rgb(...$color);

		return $this;
	}

	/**
	 * Changes the error correction level of a QrCode.
	 *
	 * @param int $level Desired error correction level.  L = 1 M = 0 Q = 3 H = 1
	 *
	 * @return $this
	 */
	public function errorCorrection(int $level)
	{
		$this->errorCorrection = ErrorCorrectionLevel::forBits($level);

		return $this;
	}

	/**
	 * Creates a margin around the QrCode.
	 *
	 * @param int $margin The desired margin in pixels around the QrCode
	 *
	 * @return $this
	 */
	public function margin($margin)
	{
		$this->margin = $margin;

		return $this;
	}

	/**
	 * Get the content of the QR Code.
	 *
	 * @return string
	 */
	public function getContentType(): string
	{
		return $this->contentType;
	}

	/**
	 * Sets the Encoding mode.
	 *
	 * @param string $encoding
	 *
	 * @return $this
	 */
	public function encoding($encoding)
	{
		$this->encoding = $encoding;

		return $this;
	}

	/**
	 * Renderer Style of the QR code instance.
	 *
	 * @return RendererStyle
	 */
	protected function rendererStyleInstance(): RendererStyle
	{
		$module = $this->module ?: SquareModule::instance();

		return new RendererStyle(
			$this->size,
			$this->margin,
			$module,
			new ModuleEye($module),
			Fill::uniformColor(
				$this->backgroundColor ?: new Gray(100),
				$this->foregroundColor ?: new Gray(0)
			)
		);
	}

	/**
	 * Created an new ImageRenderer instance.
	 *
	 * @return BaconQrCode\Renderer\ImageRenderer
	 */
	protected function imageRendererInstance()
	{
		return new ImageRenderer(
			$this->rendererStyleInstance(),
			$this->getFormatInstance()
		);
	}

	/**
	 * Format Change
	 * Three formats are currently supported; PNG, EPS, and SVG.
	 *
	 * @return \BaconQrCode\Renderer\Image\ImageBackEndInterface
	 */
	protected function getFormatInstance(): ImageBackEndInterface
	{
		switch ($this->format) {
			case 'png':
				$this->contentType = 'image/png';
				return new ImagickImageBackEnd($this->format);

			case 'eps':
				$this->contentType = 'image/eps';
				return new EpsImageBackEnd;

			default:
				$this->contentType = 'image/svg+xml';
				return new SvgImageBackEnd;
		}
	}

	/**
	 * Creates a new datatype object and then generates a QrCode.
	 *
	 * @param $method
	 * @param $arguments
	 */
	public function __call($method, $arguments)
	{
		$dataType = $this->createClass($method);

		$dataType->create($arguments);

		return $this->generate(strval($dataType));
	}

	/**
	 * Creates a new DataType class dynamically.
	 *
	 * @param string $method
	 *
	 * @return SimpleSoftwareIO\QrCode\DataTypes\DataTypeInterface
	 */
	private function createClass($method)
	{
		$class = $this->formatClass($method);

		if (!class_exists($class)) {
			throw new \BadMethodCallException();
		}

		return new $class();
	}

	/**
	 * Formats the method name correctly.
	 *
	 * @param $method
	 *
	 * @return string
	 */
	private function formatClass($method)
	{
		$method = ucfirst($method);

		return __NAMESPACE__ . '\\DataTypes\\' . $method;
	}
}

