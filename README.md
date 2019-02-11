<p align="center">
	<img src="qr.png">
</p>

An easy-to-use PHP QrCode generator.

## Introduction

> Simple QrCode is an easy to use wrapper for the popular Laravel framework based on the great work provided by Bacon/BaconQrCode. We created an interface that is familiar and easy to install for Laravel users.


## Usage

```php
use Ofcold\QrCode\Facades\QrCode;

$text = 'Happy New Year';

// Default output svg format file.
QrCode::generate($text);

// Output other file formats.
QrCode::generate($text)->format('png');

// Change the QR code color, Supported rgb and hex
// Example: 255.255.0 OR #ff0000
QrCode::generate($text)->color('#ff0000')

```

## License

This software is released under the [MIT license](https://opensource.org/licenses/MIT).
