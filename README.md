<p align="center">
	<img src="https://github.com/ofcold/qr-code/raw/master/qr.png" width="220" height="220">
</p>

An easy-to-use PHP QrCode generator.

## Introduction

> Simple QrCode is an easy to use wrapper for the popular Laravel framework based on the great work provided by [Bacon/BaconQrCode](https://github.com/Bacon/BaconQrCode). We created an interface that is familiar and easy to install for Laravel users.


## Usage

```php
use Ofcold\QrCode\Facades\QrCode;

$text = 'Happy New Year';

// Default output svg format file.
QrCode::generate($text);

// Output other file formats.
QrCode::format('png')
	->generate($text);

// Change the QR code color, Supported rgb and hex
// Example: 255.255.0 OR #ff0000
QrCode::color('#ff0000')
	->format('png')
	->generate($text);

```

## License

This software is released under the [MIT license](https://opensource.org/licenses/MIT).
