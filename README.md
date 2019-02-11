<p align="center">
	<img src="https://github.com/ofcold/qr-code/raw/master/qr.png" width="220" height="220">
	<p>Ofcold QR code</p>
</p>

An easy-to-use PHP QrCode generator.

## Introduction

> Simple QrCode is an easy to use wrapper for the popular Laravel framework based on the great work provided by [Bacon/BaconQrCode](https://github.com/Bacon/BaconQrCode). We created an interface that is familiar and easy to install for Laravel users.


## Installation

```bash
composer require ofcold/qrcode
```


## Usage

> Heads up! This method must be called last if using within a chain.

Generate by default will return a SVG image string. You can print this directly into a modern browser within Laravel's Blade system with the following:

```php
QrCode::generate($text);

// The generate method has a second parameter that will accept a filename and path to save the QrCode.
QrCode::generate($text, 'public/qrcode.svg');
```

### Basic
```php
use Ofcold\QrCode\Facades\QrCode;
use Ofcold\QrCode\HexToRgb;

$text = 'Happy New Year';

// Default output svg format file.
QrCode::generate($text);
```
### Format Change
> QrCode Generator is setup to return a SVG image by default.
Watch out! The format method must be called before any other formatting options such as size, color, backgroundColor, and margin.

```php
// Output other file formats.
QrCode::format('png')
	->generate($text);

// Change the QR code color, Supported rgb and hex
// Example: 255.255.0 OR #ff0000
QrCode::color(HexToRgb::make('#ff0000'))
	// color([255, 0, 0])
	->format('png')
	->generate($text);
```

### Size Change

> QrCode Generator will by default return the smallest size possible in pixels to create the QrCode.

```php
// You can change the size of a QrCode by using the size method. Simply specify the size desired in pixels using the following syntax:
QrCode::size(400)
	->generate($text);
```

## License

This software is released under the [MIT license](https://opensource.org/licenses/MIT).
