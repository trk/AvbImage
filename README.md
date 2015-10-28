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

### Methods List

**Resizing Images**

* [resize()](#resize)
* [widen()](#widen)
* [heighten()](#heighten)
* [fit()](#fit)
* [resizeCanvas()](#resizecanvas)
* [crop()](#crop)
* [trim()](#trim)

**Adjusting Images**

* [gamma()](#gamma)
* [brightness()](#brightness)
* [contrast()](#contrast)
* [colorize()](#colorize)
* [greyscale()](#greyscale)
* [invert()](#invert)
* [mask()](#mask)
* [flip()](#flip)

**Applying Effects**

* [filter()](#filter)
* [pixelate()](#pixelate)
* [rotate()](#rotate)
* [blur()](#blur)

**Drawing**

* [text()](#text)
* [pixel()](#pixel)
* [line()](#line)
* [rectangle()](#rectangle)
* [circle()](#circle)
* [ellipse()](#ellipse)

**Retrieving Information**

* [encode()](#encode)
* [width()](#width)
* [height()](#height)
* [mime()](#mime)
* [exif()](#exif)
* [iptc()](#iptc)

### Methods

#### Resizing Images

##### resize()

> Resizes current image based on given **width** and/or **height**. To
> contraint the resize command, pass an optional Closure **callback** as
> third parameter.

```php
$resized = $page->images()->first()->image()->resize(500, 300);
echo "<img src='{$resized->encode('data-url')}' />";
```

##### widen()

> Resizes the current image to new **width**, constraining aspect ratio.
> Pass an optional Closure **callback** as third parameter, to apply
> additional constraints like preventing possible upsizing.

```php
$widen = $page->images()->first()->image()->widen(500);
echo "<img src='{$widen->encode('data-url')}' />";
```
##### heighten()

> Resizes the current image to new **height**, constraining aspect ratio.
> Pass an optional Closure **callback** as third parameter, to apply
> additional constraints like preventing possible upsizing.

```php
$heighten = $page->images()->first()->image()->heighten(300);
echo "<img src='{$heighten->encode('data-url')}' />";
```

##### fit()

> Combine cropping and resizing to format image in a smart way. The
> method will find the best fitting aspect ratio of your given **width** and
> **height** on the current image automatically, cut it out and resize it to
> the given dimension. You may pass an optional Closure **callback** as
> third parameter, to prevent possible upsizing and a custom **position** of
> the cutout as fourth parameter.

```php
$fit = $page->images()->first()->image()->fit(600, 360);
echo "<img src='{$fit->encode('data-url')}' />";
```

##### resizeCanvas()

> Resize the boundaries of the current image to given **width** and **height**.
> An **anchor** can be defined to determine from what point of the image the
> resizing is going to happen. Set the mode to **relative** to add or
> subtract the given width or height to the actual image dimensions. You
> can also pass a **background color** for the emerging area of the image.

```php
$resizeCanvas = $page->images()->first()->image()->resizeCanvas(1280, 720, 'center', false, 'ff00ff');
echo "<img src='{$resizeCanvas->encode('data-url')}' />";
```

**crop()**

> Cut out a rectangular part of the current image with given **width**
> and **height**. Define optional **x**, **y** **coordinates** to move
> the top-left corner of the cutout to a certain position.

```php
$crop = $page->images()->first()->image()->crop(100, 100, 25, 25);
echo "<img src='{$crop->encode('data-url')}' />";
```

##### trim()

> Trim away image space in given color. Define an optional **base** to pick
> a color at a certain position and borders that should be trimmed **away**.
> You can also set an optional **tolerance** level, to trim similar colors
> and add a **feathering** border around the trimed image.

```php
$trim = $page->images()->first()->image()->trim('transparent', array('top', 'bottom'));
echo "<img src='{$trim->encode('data-url')}' />";
```

#### Adjusting Images

##### gamma()

> Performs a gamma correction operation on the current image.

```php
$gamma = $page->images()->first()->image()->gamma(1.6);
echo "<img src='{$gamma->encode('data-url')}' />";
```

##### brightness()

> Changes the brightness of the current image by the given **level**. Use
> values between **-100** for min. brightness **0** for no change and 
> **+100** for max. brightness.

```php
$brightness = $page->images()->first()->image()->brightness(35);
echo "<img src='{$brightness->encode('data-url')}' />";
```
**contrast()**

> Changes the contrast of the current image by the given **level**. Use
> values between **-100** for min. contrast **0** for no change and
> **+100** for max. contrast.

```php
$contrast = $page->images()->first()->image()->contrast(65);
echo "<img src='{$contrast->encode('data-url')}' />";
```

##### colorize()

> Change the **RGB** color values of the current image on the given channels
> **red**, **green** and **blue**. The input values are normalized so you have to
> include parameters from **100** for maximum color value **0** for no change
> and **-100** to take out all the certain color on the image.

```php
$colorize = $page->images()->first()->image()->colorize(0, 30, 0);
echo "<img src='{$colorize->encode('data-url')}' />";
```
##### greyscale()

> Turns image into a greyscale version.

```php
$greyscale = $page->images()->first()->image()->greyscale();
echo "<img src='{$greyscale->encode('data-url')}' />";
```

##### invert()

> Reverses all colors of the current image.

```php
$invert = $page->images()->first()->image()->invert();
echo "<img src='{$invert->encode('data-url')}' />";
```

##### mask()

> Apply a given **image source** as alpha mask to the current image to
> change current opacity. Mask will be resized to the current image
> size. By default a greyscale version of the mask is converted to alpha
> values, but you can set **mask_with_alpha** to apply the actual alpha
> channel. Any transparency values of the current image will be
> maintained.

```php
$mask1 = $page->images()->first()->image()->mask('public/mask.png');
echo "<img src='{$mask1->encode('data-url')}' />";

$mask2 = $page->images()->first()->image()->mask('public/alpha.png', true);
echo "<img src='{$mask2->encode('data-url')}' />";
```

##### flip()

> Mirror the current image horizontally or vertically by specifying the
> mode.

```php
$flip = $page->images()->first()->image()->flip('v');
echo "<img src='{$flip->encode('data-url')}' />";
```

#### Applying Effects

##### filter()

> Not worked on this !

##### pixelate()

> Applies a pixelation effect to the current image with a given **size** of
> pixels.

```php
$pixelate = $page->images()->first()->image()->pixelate(12);
echo "<img src='{$pixelate->encode('data-url')}' />";
```

##### rotate()

Rotate the current image counter-clockwise by a given **angle**. Optionally define a **background color** for the uncovered zone after the rotation.

```php
// rotate image 45 degrees clockwise
$rotate = $page->images()->first()->image()->rotate(-45);
echo "<img src='{$rotate->encode('data-url')}' />";
```

##### blur()

> Apply a gaussian blur filter with a optional amount on the current
> image. Use values between **0** and **100.**
> 
> Note: **Performance intensive on larger amounts of blur with GD driver.**
> **Use with care.**

```php
// apply slight blur filter
$blur1 = $page->images()->first()->image()->blur();
echo "<img src='{$blur1->encode('data-url')}' />";

// apply stronger blur
$blur2 = $page->images()->first()->image()->blur(15);
echo "<img src='{$blur2->encode('data-url')}' />";
```

#### Drawing

##### text()

> Write a **text** string to the current image at an optional **x,y basepoint
> position**. You can define more details like font-size, font-file and
> alignment via a **callback** as the fourth parameter.

```php
$text1 = $page->images()->first()->image()->text('The quick brown fox jumps over the lazy dog.', 120, 100);
echo "<img src='{$text1->encode('data-url')}' />";

$text2 = $page->images()->first()->image()->text('foo', 0, 0, function($font) {
    $font->file('foo/bar.ttf');
    $font->size(24);
    $font->color('#fdf6e3');
    $font->align('center');
    $font->valign('top');
    $font->angle(45);
});
echo "<img src='{$text2->encode('data-url')}' />";
```

##### pixel()

> Draw a single pixel in given **color** on **x**, **y** position.

```php
$pixel = $page->images()->first()->image()->pixel('#ff0000', 64, 64);
echo "<img src='{$pixel->encode('data-url')}' />";
```

##### line()

> Draw a line from **x, y point 1* to **x, y point 2** on current image. Define
> **color and/or width** of line in an optional Closure callback.

```php
$line = $page->images()->first()->image()->line(10, 10, 195, 195, function ($draw) {
    $draw->color('#f00');
    $draw->width(5);
});
echo "<img src='{$line->encode('data-url')}' />";
```

##### rectangle()

> Draw a colored rectangle on current image with top-left corner on **x,y**
> **point 1** and bottom-right corner at **x,y point 2**. Define the overall
> appearance of the shape by passing a Closure **callback** as an optional
> parameter.

```php
// draw an empty rectangle border
$rectangle1 = $page->images()->first()->image()->rectangle(10, 10, 190, 190);
echo "<img src='{$rectangle1->encode('data-url')}' />";

// draw filled red rectangle
$rectangle2 = $page->images()->first()->image()->rectangle(5, 5, 195, 195, function ($draw) {
    $draw->background('#ff0000');
});
echo "<img src='{$rectangle2->encode('data-url')}' />";
```

##### circle()

> Draw a circle at given **x, y, coordinates** with given **diameter**.
> You can define the **appearance** of the circle by an optional closure
> callback.

```php
$circle1 = $page->images()->first()->image()->circle(100, 50, 50, function ($draw) {
    $draw->background('#0000ff');
});
echo "<img src='{$circle1->encode('data-url')}' />";

$circle2 = $page->images()->first()->image()->circle(10, 100, 100, function ($draw) {
    $draw->background('#0000ff');
    $draw->border(1, '#f00');
});
echo "<img src='{$circle2->encode('data-url')}' />";
```

##### ellipse()

> Draw a **colored** ellipse at given **x, y, coordinates**. You can
> define **width** and **height** and set the **appearance** of the
> circle by an optional closure callback.

```php
$ellipse1 = $page->images()->first()->image()->ellipse(25, 30, 50, 50, function ($draw) {
    $draw->background('#0000ff');
});
echo "<img src='{$ellipse1->encode('data-url')}' />";

$ellipse2 = $page->images()->first()->image()->ellipse(60, 120, 100, 100, function ($draw) {
    $draw->background('#0000ff');
    $draw->border(1, '#ff0000');
});
echo "<img src='{$ellipse2->encode('data-url')}' />";
```

#### Retrieving Information

##### encode()

> Encodes the current image in given format and given image quality.

```php
// encode png image as jpg
$pngToJpg = $page->image()->first()->image()->encode('jpg', 75);

echo "<img src='{$pngToJpg->encode('data-url')}' />";

// encode image as data-url
echo $page->image()->first()->image()->encode('data-url');
```

##### width()

> Returns the width in pixels of the current image.

```php
echo $page->image()->first()->image()->width();
```

##### height()

> Returns the height in pixels of the current image.

```php
echo $page->images()->first()->image()->height();
```

##### mime()

> Read MIME Type of current image instance, if it's already defined.

```php
echo $page->image()->first()->image()->mime();
```

##### exif()

> Read Exif meta data from current image. Image object must be
> instantiated from file path.
> 
> Note: **PHP must be compiled in with --enable-exif to use this method.**
> **Windows users must also have the mbstring extension enabled.**

```php
$exif = $page->images()->first()->image()->exif();
echo '<pre>' . print_r($exif, true) . '</pre>';
```

##### iptc()

> Read IPTC meta data from current image.

```php
$iptc = $page->images()->first()->image()->iptc();
echo '<pre>' . print_r($iptc, true) . '</pre>';
```