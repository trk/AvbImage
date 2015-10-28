## AvbImage - Image Manipulator Module for ProcessWire

This module using [Intervention Image](https://github.com/Intervention/image) **PHP image handling and manipulation** library.

Big thansk to [@olivervogel](https://github.com/Intervention/image)

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

```
$resized = $page->image()->first()->image()->resize(500, 300);
echo "<img src='{$resized->encode('data-url')}' />";
```

**widen()**

> Resizes the current image to new **width**, constraining aspect ratio.
> Pass an optional Closure **callback** as third parameter, to apply
> additional constraints like preventing possible upsizing.

```
$widen = $page->image()->first()->image()->widen(500);
echo "<img src='{$widen->encode('data-url')}' />";
```
**heighten()**

> Resizes the current image to new **height**, constraining aspect ratio.
> Pass an optional Closure **callback** as third parameter, to apply
> additional constraints like preventing possible upsizing.

```
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
```
$fit = $page->image()->first()->image()->fit(600, 360);
echo "<img src='{$fit->encode('data-url')}' />";
```

**resizeCanvas()**

> Resize the boundaries of the current image to given **width** and **height**.
> An **anchor** can be defined to determine from what point of the image the
> resizing is going to happen. Set the mode to **relative** to add or
> subtract the given width or height to the actual image dimensions. You
> can also pass a **background color** for the emerging area of the image.
```
$resizeCanvas = $page->image()->first()->image()->resizeCanvas(1280, 720, 'center', false, 'ff00ff');
echo "<img src='{$resizeCanvas->encode('data-url')}' />";
```
**crop()**
Cut out a rectangular part of the current image with given **width** and **height**. Define optional **x**, **y** **coordinates** to move the top-left corner of the cutout to a certain position.
```
$crop = $page->image()->first()->image()->crop(100, 100, 25, 25);
echo "<img src='{$crop->encode('data-url')}' />";
```

**trim()**

> Trim away image space in given color. Define an optional **base** to pick
> a color at a certain position and borders that should be trimmed **away**.
> You can also set an optional **tolerance** level, to trim similar colors
> and add a **feathering** border around the trimed image.
```
$trim = $page->image()->first()->image()->trim('transparent', array('top', 'bottom'));
echo "<img src='{$trim->encode('data-url')}' />";
```

#### Adjusting Images

**gamma()**
**brightness()**
**contrast()**
**colorize()**
**greyscale()**
**invert()**
**mask()**
**flip()**

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