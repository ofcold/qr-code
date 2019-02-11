<?php

namespace Ofcold\QrCode;

use Illuminate\Http\Response;
use Ofcold\QrCode\Contracts\QrCodeInterface;

class QRcodeResponse extends Response
{
	/**
	 * Created an new QRcodeResponse instance.
	 *
	 * @param QrCodeInterface $qrCode
	 * @param string $text
	 */
	public function __construct(QrCodeInterface $qrCode, string $text)
	{
		parent::__construct(
			$qrCode->generate($text),
			Response::HTTP_OK,
			[
				'Content-Type' => $qrCode->getContentType()
			]
		);
	}
}
