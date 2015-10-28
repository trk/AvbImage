## AvbImage - Image Manipulator Module for ProcessWire

This module using [Intervention Image](https://github.com/Intervention/image) **PHP image handling and manipulation** library.

Big thansk to [Oliver Vogel](https://github.com/olivervogel)

### Module Author

* [İskender TOTOĞLU](http://altivebir.com)

### Requirements

- PHP >=5.4
- Fileinfo Extension

### Supported Image Libraries

- GD Library (>=2.0)
- Imagick PHP extension (>=6.5.7)

### Methods
#### Resizing Images

**resize()**

> Resizes current image based on given **width** and/or **height**. To
> contraint the resize command, pass an optional Closure **callback** as
> third parameter.

```php
$resized = $page->image()->first()->image()->resize(500, 300);
echo "<img src='{$resized->encode('data-url')}' />";
```

**widen()**

> Resizes the current image to new **width**, constraining aspect ratio.
> Pass an optional Closure **callback** as third parameter, to apply
> additional constraints like preventing possible upsizing.

```php
$widen = $page->image()->first()->image()->widen(500);
echo "<img src='{$widen->encode('data-url')}' />";
```
**heighten()**

> Resizes the current image to new **height**, constraining aspect ratio.
> Pass an optional Closure **callback** as third parameter, to apply
> additional constraints like preventing possible upsizing.

```php
$heighten = $page->image()->first()->image()->heighten(300);
echo "<img src='{$heighten->encode('data-url')}' />";
```

**fit()**

> Combine cropping and resizing to format image in a smart way. The
> method will find the best fitting aspect ratio of your given **width** and
> **height** on the current image automatically, cut it out and resize it to
> the given dimension. You may pass an optional Closure **callback** as
> third parameter, to prevent possible upsizing and a custom **position** of
> the cutout as fourth parameter.

```php
$fit = $page->image()->first()->image()->fit(600, 360);
echo "<img src='{$fit->encode('data-url')}' />";
```

**resizeCanvas()**

> Resize the boundaries of the current image to given **width** and **height**.
> An **anchor** can be defined to determine from what point of the image the
> resizing is going to happen. Set the mode to **relative** to add or
> subtract the given width or height to the actual image dimensions. You
> can also pass a **background color** for the emerging area of the image.

```php
$resizeCanvas = $page->image()->first()->image()->resizeCanvas(1280, 720, 'center', false, 'ff00ff');
echo "<img src='{$resizeCanvas->encode('data-url')}' />";
```

**crop()**

> Cut out a rectangular part of the current image with given **width**
> and **height**. Define optional **x**, **y** **coordinates** to move
> the top-left corner of the cutout to a certain position.

```php
$crop = $page->image()->first()->image()->crop(100, 100, 25, 25);
echo "<img src='{$crop->encode('data-url')}' />";
```

**trim()**

> Trim away image space in given color. Define an optional **base** to pick
> a color at a certain position and borders that should be trimmed **away**.
> You can also set an optional **tolerance** level, to trim similar colors
> and add a **feathering** border around the trimed image.

```php
$trim = $page->image()->first()->image()->trim('transparent', array('top', 'bottom'));
echo "<img src='{$trim->encode('data-url')}' />";
```

#### Adjusting Images

**gamma()**

> Performs a gamma correction operation on the current image.

```php
$gamma = $page->image()->first()->image()->gamma(1.6);
echo "<img src='{$gamma->encode('data-url')}' />";
```

**brightness()**

> Changes the brightness of the current image by the given **level**. Use
> values between **-100** for min. brightness **0** for no change and 
> **+100** for max. brightness.

```php
$brightness = $page->image()->first()->image()->brightness(35);
echo "<img src='{$brightness->encode('data-url')}' />";
```
**contrast()**

> Changes the contrast of the current image by the given **level**. Use
> values between **-100** for min. contrast **0** for no change and
> **+100** for max. contrast.

```php
$contrast = $page->image()->first()->image()->contrast(65);
echo "<img src='{$contrast->encode('data-url')}' />";
```

**colorize()**

> Change the **RGB** color values of the current image on the given channels
> **red**, **green** and **blue**. The input values are normalized so you have to
> include parameters from **100** for maximum color value **0** for no change
> and **-100** to take out all the certain color on the image.

```php
$colorize = $page->image()->first()->image()->colorize(0, 30, 0);
echo "<img src='{$colorize->encode('data-url')}' />";
```
**greyscale()**

> Turns image into a greyscale version.

```php
$greyscale = $page->image()->first()->image()->greyscale();
echo "<img src='{$greyscale->encode('data-url')}' />";
```

**invert()**

> Reverses all colors of the current image.

```php
$invert = $page->image()->first()->image()->invert();
echo "<img src='{$invert->encode('data-url')}' />";
```

**mask()**

> Apply a given **image source** as alpha mask to the current image to
> change current opacity. Mask will be resized to the current image
> size. By default a greyscale version of the mask is converted to alpha
> values, but you can set **mask_with_alpha** to apply the actual alpha
> channel. Any transparency values of the current image will be
> maintained.

```php
$mask1 = $page->image()->first()->image()->mask('public/mask.png');
echo "<img src='{$mask1->encode('data-url')}' />";

$mask2 = $page->image()->first()->image()->mask('public/alpha.png', true);
echo "<img src='{$mask2->encode('data-url')}' />";
```

**flip()**

> Mirror the current image horizontally or vertically by specifying the
> mode.

```php
$flip = $page->image()->first()->image()->flip('v');
echo "<img src='{$flip->encode('data-url')}' />";
```

#### Applying Effects

**filter()**
**pixelate()**
**rotate()**
**blur()**

#### Drawing

**text()**
**pixel()**
**line()**
**rectangle()**
**circle()**
**ellipse()**

#### Retrieving Information

**width**()
**height()**
**mime()**
**exif()**
**iptc()**