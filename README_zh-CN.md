<p align="center">
	<img src="https://github.com/ofcold/qr-code/raw/master/qr.png" width="220" height="220">
	<p>Ofcold QR code</p>
</p>

一个易于使用的PHP QrCode生成器 来自。

[English Documentation](https://github.com/ofcold/qr-code/blob/master/README_zh-CN.md)

## 介绍

> 简单的QrCode是基于[Bacon/BaconQrCode](https://github.com/Bacon/BaconQrCode)提供的出色工作的流行Laravel框架的易用包装器。 我们为Laravel用户创建了一个熟悉且易于安装的界面。

[Example](https://github.com/ofcold/laravel-qr-code-test)

## 安装

```bash
composer require ofcold/qrcode
```


## Usage

> `注意`！ 如果在链中使用，则必须最后调用此方法。

默认情况下生成将返回SVG图像字符串。 您可以使用以下内容将其直接打印到Laravel Blade 模版中：
```php
QrCode::generate($text);

// generate方法有第二个参数，它接受一个文件名和路径来保存QrCode。
QrCode::generate($text, 'public/qrcode.svg');
```

### 基本
```php
use Ofcold\QrCode\Facades\QrCode;
use Ofcold\QrCode\HexToRgb;

$text = 'Happy New Year';

// 默认输出SVG格式文件
QrCode::generate($text);
```

### Response

> 浏览器直接输出图像。

#### 使用 `QRcodeResponse`
```php
use Ofcold\QrCode\QRcodeResponse

new QRcodeResponse(QrCode::generate($text))
```

#### use `response()`
```php

$qr = QrCode::color('#ff0000');

response($qr->generate($text))->header('Content-Type', $qr->getContentType())
```

### 格式
> QrCode Generator设置为默认返回SVG图像。
注意！ 必须在任何其他格式设置选项（如size，color，backgroundColor和margin）之前调用format方法。

```php
// Output other file formats.
QrCode::format('png')
	->generate($text);
```

### 大小

> QrCode Generator默认返回可能的最小尺寸（以像素为单位）来创建QrCode。

```php
// 您可以使用size方法更改QrCode的大小。 只需使用以下语法指定所需的大小（以像素为单位）：
QrCode::size(400)
	->generate($text);
```

### 颜色

> 更改QrCode的颜色时要小心。 有些读者在阅读彩色QrCodes时非常困难。

```
// 更改QR码颜色，支持rgb和十六进制
// 所有颜色必须以RGB（红绿蓝）表示。 您可以使用以下内容更改QrCode的颜色: 255.255.0 OR #ff0000
QrCode::color(HexToRgb::make('#ff0000'))
	// color([255, 0, 0])
	->format('png')
	->generate($text);
```

### 边距
> 还支持更改QrCode周围边距的功能。 只需使用以下语法指定所需的边距即可:

```php
QrCode::margin(100)->generate($text);
```

### Error Correction
> 更改纠错级别很容易。 只需使用以下语法： L = 1 M = 0 Q = 3 H = 1

```php
QrCode::errorCorrection(1)->generate($text);
```

### Encoding
> 更改用于构建QrCode的字符编码。 默认情况下，ISO-8859-1被选为编码器。 阅读有关字符编码的更多信息您可以将其更改为以下任何一项：

```php
QrCode::encoding('UTF-8')->generate($text);
```
字符编码器: ISO-8859-1, ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6, ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-11, ISO-8859-12, ISO-8859-13, ISO-8859-14, ISO-8859-15, ISO-8859-16, SHIFT-JIS, WINDOWS-1250, WINDOWS-1251, WINDOWS-1252, WINDOWS-1256, UTF-16BE, UTF-8, ASCII, GBK, EUC-KR, 

> 无法将内容编码为ISO-8859-1的错误意味着正在使用错误的字符编码类型。 如果您不确定，我们建议使用UTF-8。


### 合并（通常为二维码增加LOGO）
> 合并方法将图像合并到QrCode上。 这通常用于在QrCode中放置徽标。

```php
QrCode::merge($filename, $percentage, $absolute);

// 生成QrCode，图像居中于中间。
QrCode::format('png')->merge('path-to-image.png')->generate();

// 生成QrCode，图像居中于中间。 插入的图像占用QrCode的20％。
QrCode::format('png')->merge('path-to-image.png', .2)->generate();

QrCode::format('png')->merge('http://ofcold.com/icon.png', .2, true)->generate();
```

> 合并方法目前仅支持PNG。如果$absolute设置为false，则文件路径相对于应用程序基本路径。 将此变量更改为true以使用绝对路径。
使用merge方法时，应使用高级别的错误纠正，以确保QrCode仍然可读。 我们建议使用errorCorrection(2)。

### 合并二进制字符串
> mergeString方法可用于实现与merge调用相同的方法，但它允许您提供文件的字符串表示而不是文件路径。 在使用存储外观时，这非常有用。 它的界面与合并调用非常相似。

```php
QrCode::mergeString(Storage::get('path/to/image.png'), $percentage);

// 生成QrCode，图像居中于中间。
QrCode::format('png')->mergeString(Storage::get('path/to/image.png'))->generate();

QrCode::format('png')->mergeString(Storage::get('path/to/image.png'), .2)->generate();
```
> 与普通合并调用一样，此时仅支持PNG。 这同样适用于纠错。


### 高级使用

所有方法都支持链接。 必须最后调用`generate`方法，并且必须首先调用任何格式更改。 例如，您可以运行以下任何一项：

```php
QrCode::size(250)->color(150,90,10)->backgroundColor(10,14,244)->generate($text);
QrCode::format('png')->size(399)->color(40,40,40)->generate($text);
```

## 助手

### 什么是帮手？

帮助程序是创建QrCodes的简便方法，可以使读者在扫描时执行某个操作。

### 比特币

这个助手生成一个可扫描的比特币来发送付款。[More information](https://bitco.in/en/developer-guide#plain-text)

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

此助手生成一个电子邮件qr代码，可以填写电子邮件地址，主题和正文。

```php
QrCode::email($to, $subject, $body);

// 填写地址
QrCode::email('foo@bar.com');

// 填写电子邮件的地址，主题和正文。
QrCode::email('foo@bar.com', '这是主题', '这是内容');

// 只填写电子邮件的主题和正文。
QrCode::email(null, '这是主题', '这是内容');
```

### 地理

此帮助程序生成手机可以读取的纬度和经度，并在Google地图或类似应用程序中打开该位置。

```php
QrCode::geo($latitude, $longitude);

QrCode::geo(37.822214, -122.481769);
```

### 电话号码

该助手生成一个可以扫描的QrCode，然后拨打一个号码。

```php
QrCode::phoneNumber($phoneNumber);

QrCode::phoneNumber('18898726543');
QrCode::phoneNumber('1-800-MY-APPLE');

```

### SMS (短信)

该帮助程序生成SMS消息，可以使用发送到地址和消息正文预先填充。

```php
QrCode::SMS($phoneNumber, $message);

// 创建填写了手机号码的文本消息。
QrCode::SMS('555-555-5555');

// 创建包含手机号码和消息的文本消息。
QrCode::SMS('555-555-5555', 'Body of the message');

```

### Wi-Fi

这个助手可以制作可扫描的QrCodes，可以将手机连接到WiFI网络。

```php
QrCode::wiFi([
	'encryption' => '加密级别',
	'ssid'		 => '网络名称',
	'password'	 => '网络密码',
	'hidden'	 => '网络是否是隐藏的SSID。'
]);

// 连接到开放的WiFi网络。
QrCode::wiFi([
	'ssid' => '网络名称',
]);

// 连接到开放的隐藏WiFi网络。
QrCode::wiFi([
	'ssid' => '网络名称',
	'hidden' => 'true'
]);

// 连接到安全的WiFi网络。
QrCode::wiFi([
	'ssid' => '网络名称',
	'encryption' => 'WPA',
	'password' => '我的密码'
]);

```


## License

该软件发布于 [MIT license](https://opensource.org/licenses/MIT).
