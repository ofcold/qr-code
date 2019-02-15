<p align="center">
	<img src="https://github.com/ofcold/qr-code/raw/master/qr.png" width="220" height="220">
	<p>Ofcold QR code</p>
</p>

An easy-to-use PHP QrCode generator.

[简体中文文档](https://github.com/ofcold/qr-code/blob/master/README_zh-CN.md)

## Introduction

> Simple QrCode is an easy to use wrapper for the popular Laravel framework based on the great work provided by [Bacon/BaconQrCode](https://github.com/Bacon/BaconQrCode). We created an interface that is familiar and easy to install for Laravel users.

[Example](https://github.com/ofcold/laravel-qr-code-test)

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

### Response

> The browser directly outputs the image.

#### use `QRcodeResponse`
```php
use Ofcold\QrCode\QRcodeResponse

new QRcodeResponse(QrCode::generate($text))
```

#### use `response()`
```php

$qr = QrCode::color('#ff0000');

response($qr->generate($text))->header('Content-Type', $qr->getContentType())
```

### Format Change
> QrCode Generator is setup to return a SVG image by default.
Watch out! The format method must be called before any other formatting options such as size, color, backgroundColor, and margin.

```php
// Output other file formats.
QrCode::format('png')
	->generate($text);
```

### Size Change

> QrCode Generator will by default return the smallest size possible in pixels to create the QrCode.

```php
// You can change the size of a QrCode by using the size method. Simply specify the size desired in pixels using the following syntax:
QrCode::size(400)
	->generate($text);
```

### Color Change

> Be careful when changing the color of a QrCode. Some readers have a very difficult time reading QrCodes in color.

```
// Change the QR code color, Supported rgb and hex
// All colors must be expressed in RGB (Red Green Blue). You can change the color of a QrCode by using the following: 255.255.0 OR #ff0000
QrCode::color(HexToRgb::make('#ff0000'))
	// color([255, 0, 0])
	->format('png')
	->generate($text);
```

### Margin Change
> The ability to change the margin around a QrCode is also supported. Simply specify the desired margin using the following syntax:

```php
QrCode::margin(100)->generate($text);
```

### Error Correction
> Changing the level of error correction is easy. Just use the following syntax: L = 1 M = 0 Q = 3 H = 1

```php
QrCode::errorCorrection(1)->generate($text);
```

### Encoding
> Change the character encoding that is used to build a QrCode. By default ISO-8859-1 is selected as the encoder. Read more about character encoding You can change this to any of the following:
```php
QrCode::encoding('UTF-8')->generate($text);
```
Character Encoder: ISO-8859-1, ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6, ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-11, ISO-8859-12, ISO-8859-13, ISO-8859-14, ISO-8859-15, ISO-8859-16, SHIFT-JIS, WINDOWS-1250, WINDOWS-1251, WINDOWS-1252, WINDOWS-1256, UTF-16BE, UTF-8, ASCII, GBK, EUC-KR, 

> An error of Could not encode content to ISO-8859-1 means that the wrong character encoding type is being used. We recommend UTF-8 if you are unsure.


### Merge
> The merge method merges an image over a QrCode. This is commonly used to placed logos within a QrCode.

```php
QrCode::merge($filename, $percentage, $absolute);

// Generates a QrCode with an image centered in the middle.
QrCode::format('png')->merge('path-to-image.png')->generate();

// Generates a QrCode with an image centered in the middle.  The inserted image takes up 20% of the QrCode.
QrCode::format('png')->merge('path-to-image.png', .2)->generate();

// Generates a QrCode with an image centered in the middle.  The inserted image takes up 20% of the QrCode.
QrCode::format('png')->merge('http://ofcold.com/icon.png', .2, true)->generate();
```

> The merge method only supports PNG at this time. The filepath is relative to app base path if $absolute is set to false. Change this variable to true to use absolute paths.
You should use a high level of error correction when using the merge method to ensure that the QrCode is still readable. We recommend using errorCorrection(1).

### Merge Binary String
> The mergeString method can be used to achieve the same as the merge call, except it allows you to provide a string representation of the file instead of the filepath. This is usefull when working with the Storage facade. It's interface is quite similar to the merge call.

```php
QrCode::mergeString(Storage::get('path/to/image.png'), $percentage);

// Generates a QrCode with an image centered in the middle.
QrCode::format('png')->mergeString(Storage::get('path/to/image.png'))->generate();

// Generates a QrCode with an image centered in the middle.  The inserted image takes up 20% of the QrCode.
QrCode::format('png')->mergeString(Storage::get('path/to/image.png'), .2)->generate();
```
> As with the normal merge call, only PNG is supported at this time. The same applies for error correction, high levels are recommened.


### Advance Usage

All methods support chaining. The `generate` method must be called last and any format change must be called first. For example you could run any of the following:

```php
QrCode::size(250)->color(150,90,10)->backgroundColor(10,14,244)->generate($text);
QrCode::format('png')->size(399)->color(40,40,40)->generate($text);
```

## Helpers

### What are helpers?

Helpers are an easy way to create QrCodes that cause a reader to perform a certain action when scanned.

### BitCoin

This helpers generates a scannable bitcoin to send payments. [More information](https://bitco.in/en/developer-guide#plain-text)

```php
QrCode::BTC($address, $amount);

//Sends a 0.334BTC payment to the address
QrCode::BTC('bitcoin address', 0.334);

// Sends a 0.334BTC payment to the address with some optional arguments
QrCode::size(500)->BTC('address', 0.0034, [
	'label' => 'my label',
	'message' => 'my message',
	'returnAddress' => 'https://www.returnaddress.com'
]);

```

### E-Mail

This helper generates an e-mail qrcode that is able to fill in the e-mail address, subject, and body.

```php
QrCode::email($to, $subject, $body);

// Fills in the to address
QrCode::email('foo@bar.com');

// Fills in the to address, subject, and body of an e-mail.
QrCode::email('foo@bar.com', 'This is the subject.', 'This is the message body.');

// Fills in just the subject and body of an e-mail.
QrCode::email(null, 'This is the subject.', 'This is the message body.');
```

### Geo

This helper generates a latitude and longitude that a phone can read and open the location up in Google Maps or similar app.

```php
QrCode::geo($latitude, $longitude);

QrCode::geo(37.822214, -122.481769);
```

### Phone Number

This helper generates a QrCode that can be scanned and then dials a number.

```php
QrCode::phoneNumber($phoneNumber);

QrCode::phoneNumber('18898726543');
QrCode::phoneNumber('1-800-MY-APPLE');

```

### SMS (Text Messages)

This helper makes SMS messages that can be prefilled with the send to address and body of the message.

```php
QrCode::SMS($phoneNumber, $message);

// Creates a text message with the number filled in.
QrCode::SMS('555-555-5555');

// Creates a text message with the number and message filled in.
QrCode::SMS('555-555-5555', 'Body of the message');

```

### Wi-Fi

This helpers makes scannable QrCodes that can connect a phone to a WiFI network.

```php
QrCode::wiFi([
	'encryption' => 'WPA/WEP',
	'ssid' => 'SSID of the network',
	'password' => 'Password of the network',
	'hidden' => 'Whether the network is a hidden SSID or not.'
]);

// Connects to an open WiFi network.
QrCode::wiFi([
	'ssid' => 'Network Name',
]);

// Connects to an open, hidden WiFi network.
QrCode::wiFi([
	'ssid' => 'Network Name',
	'hidden' => 'true'
]);

// Connects to an secured, WiFi network.
QrCode::wiFi([
	'ssid' => 'Network Name',
	'encryption' => 'WPA',
	'password' => 'myPassword'
]);

```


## License

This software is released under the [MIT license](https://opensource.org/licenses/MIT).
