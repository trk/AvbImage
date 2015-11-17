<?php

require __DIR__ . '/vendor/autoload.php';
use Intervention\Image\ImageManager;

/**
 * Class AvbImageManager
 *
 * @author          : İskender TOTOĞLU, @ukyo (community), @trk (Github)
 * @website         : http://altivebir.com.tr
 * @projectWebsite  : https://github.com/trk/AvbImage
 */
class AvbImageManager extends Wire {
    // Function Triggers : This is for true suffix replacement
    protected $triggers = NULL;
    // Target PATH
    protected $targetPath=NULL;
    // Image suffix
    protected $suffix = NULL;
    // Generated Suffix for IMAGE
    protected $_suffix = NULL;

    // Called methods and options as suffix
    protected $suffixArray = array();
    // Default width height values
    protected $suffixWidth = '0';
    protected $suffixHeight = '0';
    // Default suffix template
    protected $suffixTemplate = ".{width}x{height}{separator}{values}";
    // Suffix shortcuts for apply suffix | {separator}VALUE{separator}SHORTCUT
    protected $suffixes = array(
        'blur' => 'bl',
        'brightness' => 'br',
        'canvas' => 'ca',
        'circle' => 'ci',
        'colorize' => 'col',
        'contrast' => 'con',
        'crop' => 'cr',
        'ellipse' => 'el',
        'fill' => 'fi',
        'flip' => 'fl',
        'fit' => 'fit',
        'gamma' => 'ga',
        'greyscale' => 'gr',
        'heighten' => 'he',
        'insert' => 'ins',
        'interlace' => 'int',
        'invert' => 'inv',
        'limitColors' => 'lim',
        'line' => 'lin',
        'mask' => 'ma',
        'opacity' => 'op',
        'pickColor' => 'pic',
        'pixel' => 'pxl',
        'pixelate' => 'pxlt',
        'polygon' => 'pol',
        'rectangle' => 'rec',
        'resize' => 'res',
        'resizeCanvas' => 'reca',
        'rotate' => 'ro',
        'sharpen' => 'sh',
        'text' => 'txt',
        'trim' => 'tr',
        'widen' => 'wi'
    );

    // Separator for suffix
    protected $suffixSeparator;
    // PageImage
    protected $pageImage=NULL;
    // SourceImageFile
    protected $sourceImage=NULL;
    // Current Image
    protected $image=NULL;
    protected $sourceFilename=NULL;
    protected $sourceFileExtension=NULL;

    // Target file options
    protected $hasTarget=FALSE;
    protected $targetFilename;
    protected $targetFileExtension;
    protected $targetFullFilename;
    protected $targetFilePath;
    protected $targetFileFullPath;

    /**
     * Config
     *
     * @var array
     */
    public $config = array(
        'driver' => 'gd',
        'pageImage' => null,
        'sourceImage' => null,
        'suffix' => null,
        'md5-suffix' => null,
        'suffix-separator' => '-',
        'targetPath' => NULL
    );

    /**
     * AvbImageManager instance
     *
     * @param array $config
     * @throws WireException
     */
    public function __construct(array $config = array())
    {
        // Set Custom Configs
        $this->configure($config);

        // Check source exist?
        if(!is_null($this->config['pageImage']) && file_exists($this->config['pageImage']->filename)) {
            $this->pageImage = $this->config['pageImage'];
            $this->image = $this->pageImage->filename;
        } elseif(!is_null($this->config['sourceImage']) && file_exists($this->config['sourceImage'])) {
            $this->image = $this->config['sourceImage'];
        } else {
            throw new WireException('Source image could not found, please be sure image file exist.');
        }

        // Set Source Filename and Extension
        $this->sourceFilename = pathinfo($this->image, PATHINFO_FILENAME);
        $this->sourceFileExtension = "." . pathinfo($this->image, PATHINFO_EXTENSION);

        if(!is_null($this->config['targetPath'])) {
            $this->targetPath = $this->config['targetPath'];
        } else {
            $this->targetPath = pathinfo($this->image, PATHINFO_DIRNAME) . "/";
        }

        // Setup Suffix For Check Image Exist ?
        if(!is_null($this->config['suffix'])) {
            $this->suffix = $this->config['suffix'];
        } else {
            $this->suffix = array();
        }

        $this->suffixSeparator = $this->config['suffix-separator'];

    }

    /**
     * Overrides configuration settings
     *
     * @param array $config
     * @return $this
     */
    public function configure(array $config = array()) {
        $this->config = array_replace($this->config, $config);
        return $this;
    }

    /**
     * Backups current image state as fallback for reset method under an optional name.
     * Overwrites older state on every call, unless a different name is passed.
     *
     * @param string $name
     * @return $this
     */
    public function backup($name = 'default') {
        return $this->returnTrigger('backup', $name);
    }

    /**
     * Returns current image backup
     *
     * @param string $name
     * @return mixed
     */
    public function getBackup($name = null) {
        return $this->returnTrigger('getBackup', $name);
    }

    /**
     * Returns all backups attached to image
     *
     * @return array
     */
    public function getBackups() {
        return $this->returnTrigger('getBackups');
    }

    /**
     * Apply a gaussian blur filter with a optional amount on the current image. Use values between 0 and 100.
     *
     * @param int $amount
     * @return $this
     */
    public function blur($amount=1) {
        $this->suffixArray['blur'] = "amo{$amount}";
        $this->triggers['blur'] = array($amount);
        return $this;
    }

    /**
     * Changes the brightness of the current image by the given level.
     * Use values between -100 for min. brightness. 0 for no change and +100 for max. brightness.
     *
     * @param int $level
     * @return $this
     */
    public function brightness($level=50) {
        $this->suffixArray['brightness'] = "lev{$level}";
        $this->triggers['brightness'] = array($level);
        return $this;
    }

    /**
     * Factory method to create a new empty image instance with given width and height.
     * You can define a background-color optionally. By default the canvas background is transparent.
     *
     * @param $width
     * @param $height
     * @param null $bgcolor
     * @return $this
     */
    public function canvas($width, $height, $bgcolor = null) {
        $this->suffixWidth = $width;
        $this->suffixHeight = $height;
        $this->suffixArray['canvas'] = (!is_null($bgcolor)) ? "bg" . str_replace("#", "", $bgcolor) : "";
        $this->triggers['canvas'] = array($width, $height, $bgcolor);
        return $this;
    }

    /**
     * Draw a circle at given x, y, coordinates with given radius.
     * You can define the appearance of the circle by an optional closure callback.
     *
     * @param $radius
     * @param $x
     * @param $y
     * @param Closure|null $callback
     * @return $this
     */
    public function circle($radius, $x, $y, $callback = null) {
        $this->suffixArray['circle'] = $radius . "x" . $x . "x" . $y;
        $this->triggers['circle'] = array($radius, $x, $y, $callback);
        return $this;
    }

    /**
     * Change the RGB color values of the current image on the given channels red, green and blue.
     * The input values are normalized so you have to include parameters from 100 for maximum color value.
     * 0 for no change and -100 to take out all the certain color on the image.
     *
     * @param $red
     * @param $green
     * @param $blue
     * @return $this
     */
    public function colorize($red, $green, $blue) {
        $this->suffixArray['colorize'] = "red{$red}gre{$green}blu{$blue}";
        $this->triggers['colorize'] = array($red, $green, $blue);
        return $this;
    }

    /**
     * Changes the contrast of the current image by the given level.
     * Use values between -100 for min. contrast 0 for no change and +100 for max. contrast.
     *
     * @param int $level
     * @return $this
     */
    public function contrast($level=65) {
        $this->suffixArray['contrast'] = "con{$level}";
        $this->triggers['contrast'] = array($level);
        return $this;
    }

    /**
     * Cut out a rectangular part of the current image with given width and height.
     * Define optional x,y coordinates to move the top-left corner of the cutout to a certain position.
     *
     * @param $width
     * @param $height
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function crop($width, $height, $x = null, $y = null) {

        $this->suffixWidth = $width;
        $this->suffixHeight = $height;

        $suffixX = (is_null($x)) ? "" : "x{$x}";
        $suffixY = (is_null($y)) ? "" : "x{$y}";
        $this->suffixArray['crop'] = $suffixX . $suffixY;

        $this->triggers['crop'] = array($width, $height, $x, $y);
        return $this;
    }

    /**
     * Frees memory associated with the current image instance before the PHP script ends.
     * Normally resources are destroyed automatically after the script is finished.
     *
     * @return $this
     */
    public function destroy() {
        return $this->returnTrigger('destroy');
    }

    /**
     * Draw a colored ellipse at given x, y, coordinates.
     * You can define width and height and set the appearance of the circle by an optional closure callback.
     *
     * @param $width
     * @param $height
     * @param $x
     * @param $y
     * @param null $callback
     * @return $this
     */
    public function ellipse($width, $height, $x, $y, $callback = null) {

        $this->suffixWidth = $width;
        $this->suffixHeight = $height;

        $this->suffixArray['ellipse'] = "x{$x}y{$y}";

        $this->triggers['ellipse'] = array($width, $height, $x, $y, $callback);
        return $this;
    }

    /**
     * Read Exif meta data from current image.
     *
     * @param null $key
     * @return mixed
     */
    public function exif($key = null) {
        return $this->returnTrigger('exif', array($key));
    }

    /**
     * Read Iptc meta data from current image.
     *
     * @param null $key
     * @return mixed
     */
    public function iptc($key = null) {
        return $this->returnTrigger('iptc', array($key));
    }

    /**
     * Fill current image with given color or another image used as tile for filling.
     * Pass optional x, y coordinates to start at a certain point.
     *
     * @param $filling
     * @param null $x
     * @param null $y
     * @return $this
     */
    public function fill($filling, $x = null, $y = null) {
        $suffixX = (is_null($x)) ? "" : "x{$x}";
        $suffixY = (is_null($y)) ? "" : "x{$y}";
        $this->suffixArray['fill'] = $suffixX . $suffixY;

        $this->triggers['fill'] = array($filling, $x, $y);

        return $this;
    }

    /**
     * Mirror the current image horizontally or vertically by specifying the mode.
     *
     * @param string $mode
     * @return $this
     */
    public function flip($mode = 'h') {
        $this->suffixArray['flip'] = "mod{$mode}";
        $this->triggers['fill'] = array($mode);
        return $this;
    }

    /**
     * Combine cropping and resizing to format image in a smart way.
     * The method will find the best fitting aspect ratio of your given width and height on the current image automatically, cut it out and resize it to the given dimension.
     * You may pass an optional Closure callback as third parameter, to prevent possible upsizing and a custom position of the cutout as fourth parameter.
     *
     * @param $width
     * @param null $height
     * @param null $callback
     * @param string $position
     * @return $this
     */
    public function fit($width, $height = null, $callback = null, $position = 'center') {

        $this->suffixWidth = $width;
        if(!is_null($height)) $this->suffixHeight = $height;

        $this->suffixArray['fit'] = "pos{$position}";

        $this->triggers['fit'] = array($width, $height, $callback, $position);
        return $this;
    }

    /**
     * Performs a gamma correction operation on the current image.
     *
     * @param $correction
     * @return $this
     */
    public function gamma($correction) {
        $this->suffixArray['gamma'] = "cor{$correction}";
        $this->triggers['gamma'] = array($correction);
        return $this;
    }

    /**
     * Turns image into a greyscale version.
     */
    public function greyscale() {
        $this->suffixArray['greyscale'] = "";
        $this->triggers['greyscale'] = array();
        return $this;
    }

    /**
     * Calculates current image height
     *
     * @return integer
     */
    public function height() {
        return $this->returnTrigger('getHeight');
    }

    /**
     * Resizes the current image to new height, constraining aspect ratio.
     * Pass an optional Closure callback as third parameter, to apply additional constraints like preventing possible upsizing.
     *
     * @param $height
     * @param null $callback
     * @return $this
     */
    public function heighten($height, $callback = null) {
        $this->suffixHeight = $height;
        $this->suffixArray['heighten'] = '';
        $this->triggers['heighten'] = array($height, $callback);
        return $this;
    }

    /**
     * Paste a given image source over the current image with an optional position and a offset coordinate.
     * This method can be used to apply another image as watermark because the transparency values are maintained.
     *
     * @param mixed $source
     * @param string $position
     * @param int $x
     * @param int $y
     * @return $this
     */
    public function insert($source, $position = 'top-left', $x = 0, $y = 0) {
        $this->suffixArray['insert'] = "pos{$position}x{$x}y{$y}";
        $this->triggers['insert'] = array($source, $position, $x, $y);
        return $this;
    }

    /**
     * Determine whether an image should be encoded in interlaced or standard mode by toggling interlace mode with a boolean parameter.
     * If an JPEG image is set interlaced the image will be processed as a progressive JPEG.
     *
     * @param bool|true $interlace
     * @return $this
     */
    public function interlace($interlace = true) {
        $this->suffixArray['interlace'] = '';
        $this->triggers['interlace'] = array($interlace);
        return $this;
    }

    /**
     * Reverses all colors of the current image.
     *
     * @return $this
     */
    public function invert() {
        $this->suffixArray['invert'] = '';
        $this->triggers['invert'] = array();
        return $this;
    }

    /**
     * Method converts the existing colors of the current image into a color table with a given maximum count of colors.
     * The function preserves as much alpha channel information as possible and blends transarent pixels against a optional matte color.
     *
     * @param int $count
     * @param mixed|null $matte
     * @return $this
     */
    public function limitColors($count, $matte = null) {
        $this->suffixArray['limitColors'] = "cou{$count}" . ((!is_null($matte)) ? "mat{$matte}" : '');
        $this->triggers['limitColors'] = array($count, $matte);
        return $this;
    }

    /**
     * Draw a line from x,y point 1 to x,y point 2 on current image. Define color and/or width of line in an optional Closure callback.
     *
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @param Closure|null $callback
     * @return $this
     */
    public function line($x1, $y1, $x2, $y2, $callback = null) {
        $this->suffixArray['line'] = "x1{$x1}y1{$y1}x2{$x2}y2{$y2}";
        $this->triggers['line'] = array($x1, $y1, $x2, $y2, $callback);
        return $this;
    }

    /**
     * Apply a given image source as alpha mask to the current image to change current opacity.
     * Mask will be resized to the current image size.
     * By default a greyscale version of the mask is converted to alpha values,
     * but you can set mask_with_alpha to apply the actual alpha channel.
     * Any transparency values of the current image will be maintained.
     *
     * @param mixed $source
     * @param bool $mask_with_alpha
     * @return $this
     */
    public function mask($source, $mask_with_alpha) {
        $this->suffixArray['mask'] = '';
        $this->triggers['mask'] = array($source, $mask_with_alpha);
        return $this;
    }

    /**
     * Set the opacity in percent of the current image ranging from 100% for opaque and 0% for full transparency.
     *
     * @param int $transparency
     * @return $this
     */
    public function opacity($transparency) {
        $this->suffixArray['opacity'] = "tra{$transparency}";
        $this->triggers['opacity'] = array($transparency);
        return $this;
    }

    /**
     * This method reads the EXIF image profile setting 'Orientation' and performs a rotation on the image to display the image correctly.
     *
     * @return $this
     */
    public function orientate() {
        $this->suffixArray['orientate'] = '';
        $this->triggers['orientate'] = array();
        return $this;
    }

    /**
     * Pick a color at point x, y out of current image and return in optional given format.
     *
     * @param int $x
     * @param int $y
     * @param string $format
     * @return $this
     */
    public function pickColor($x, $y, $format = 'array') {
        return $this->returnTrigger('pickColor', array($x, $y, $format));
    }

    /**
     * Draw a single pixel in given color on x, y position.
     *
     * @param mixed $color
     * @param int $x
     * @param int $y
     * @return $this
     */
    public function pixel($color, $x, $y) {
        $this->suffixArray['pixel'] = "col" . str_replace('#', '', $color) . "x{$x}y{$y}";
        $this->triggers['pixel'] = array($color, $x, $y);
        return $this;
    }

    /**
     * Applies a pixelation effect to the current image with a given size of pixels.
     *
     * @param int $size
     * @return $this
     */
    public function pixelate($size) {
        $this->suffixArray['pixelate'] = "size{$size}";
        $this->triggers['pixelate'] = array($size);
        return $this;
    }

    /**
     * Draw a colored polygon with given points.
     * You can define the appearance of the polygon by an optional closure callback.
     *
     * @param array $points
     * @param Closure|null $callback
     * @return $this
     */
    public function polygon(array $points, $callback = null) {
        $suffix = "";
        foreach($points as $point) $suffix .= $point;

        $this->suffixArray['polygon'] = "poi{$suffix}";

        $this->triggers['pixelate'] = array($points, $callback);
        return $this;
    }

    /**
     * Draw a colored rectangle on current image with top-left corner on x,y point 1 and bottom-right corner at x,y point 2.
     * Define the overall appearance of the shape by passing a Closure callback as an optional parameter.
     *
     * @param int $x1
     * @param int $y1
     * @param int $x2
     * @param int $y2
     * @param Closure|null $callback
     * @return $this
     */
    public function rectangle($x1, $y1, $x2, $y2, $callback = null) {
        $this->suffixArray['rectangle'] = "x1{$x1}y1{$y1}x2{$x2}y2{$y2}";
        $this->triggers['rectangle'] = array($x1, $y1, $x2, $y2, $callback);
        return $this;
    }

    /**
     * Resets all of the modifications to a state saved previously by backup under an optional name.
     *
     * @param string $name
     * @return $this
     */
    public function reset($name = 'default') {
        return $this->returnTrigger('reset', array($name));
    }

    /**
     * Resizes current image based on given width and/or height.
     * To contraint the resize command, pass an optional Closure callback as third parameter.
     *
     * @param int $width
     * @param int $height
     * @param Closure|null $callback
     * @return $this
     */
    public function resize($width, $height, $callback = null) {
        $this->suffixWidth = $width;
        $this->suffixHeight = $height;
        $this->suffixArray['resize'] = "";
        $this->triggers['resize'] = array($width, $height, $callback);
        return $this;
    }

    /**
     * Resize the boundaries of the current image to given width and height.
     * An anchor can be defined to determine from what point of the image the resizing is going to happen.
     * Set the mode to relative to add or subtract the given width or height to the actual image dimensions.
     * You can also pass a background color for the emerging area of the image.
     *
     * @param int $width
     * @param int $height
     * @param string $anchor
     * @param bool|false $relative
     * @param mixed $bgcolor
     * @return $this
     */
    public function resizeCanvas($width, $height, $anchor = 'center', $relative = false, $bgcolor = '#000000') {
        $this->suffixWidth = $width;
        $this->suffixHeight = $height;
        $this->suffixArray['resizeCanvas'] = "anc{$anchor}bg" . str_replace('#', '', $bgcolor);
        $this->triggers['resizeCanvas'] = array($width, $height, $anchor, $relative, $bgcolor);
        return $this;
    }

    /**
     * Sends HTTP response with current image in given format and quality.
     *
     * @param string|null $format
     * @param int $quality
     * @return $this
     */
    public function response($format = null, $quality = 90) {
        return $this->returnTrigger('response', array($format, $quality));
    }

    /**
     * Rotate the current image counter-clockwise by a given angle.
     * Optionally define a background color for the uncovered zone after the rotation.
     *
     * @param float $angle
     * @param string $bgcolor
     * @return $this
     */
    public function rotate($angle, $bgcolor = '#000000') {
        $this->suffixArray['rotate'] = "ang{$angle}bg" . str_replace('#', '', $bgcolor);
        $this->triggers['rotate'] = array($angle, $bgcolor);
        return $this;
    }

    /**
     * Sharpen current image with an optional amount. Use values between 0 and 100.
     *
     * @param int $amount
     * @return $this
     */
    public function sharpen($amount = 10) {
        $this->suffixArray['sharpen'] = "shar{$amount}";
        $this->triggers['sharpen'] = array($amount);
        return $this;
    }

    /**
     * Write a text string to the current image at an optional x,y basepoint position.
     * You can define more details like font-size, font-file and alignment via a callback as the fourth parameter.
     *
     * @param string $text
     * @param int $x
     * @param int $y
     * @param $callback
     * @return $this
     */
    public function text($text, $x = 0, $y = 0, $callback = null) {
        $this->suffixArray['text'] = "x{$x}y{$y}";
        $this->triggers['text'] = array($text, $x, $y, $callback);
        return $this;
    }

    /**
     * Trim away image space in given color.
     * Define an optional base to pick a color at a certain position and borders that should be trimmed away.
     * You can also set an optional tolerance level, to trim similar colors and add a feathering border around the trimed image.
     *
     * @param string $base
     * @param array $away
     * @param int $tolerance
     * @param int $feather
     * @return $this
     */
    public function trim($base = 'top-left', $away = array('top', 'bottom', 'left', 'right'), $tolerance = 0, $feather = 0) {
        $awaySuffix = "";
        foreach($away as $aw) $awaySuffix .= $aw;

        $this->suffixArray['trim'] = "ba{$base}aw{$awaySuffix}tol{$tolerance}fea{$feather}";
        $this->triggers['trim'] = array($base, $away, $tolerance, $feather);
        return $this;
    }

    /**
     * Calculates current image width
     *
     * @return integer
     */
    public function width() {
        return $this->returnTrigger('getWidth');
    }

    /**
     * Resizes the current image to new width, constraining aspect ratio.
     * Pass an optional Closure callback as third parameter, to apply additional constraints like preventing possible upsizing.
     *
     * @param int $width
     * @param Closure|null $callback
     * @return $this
     */
    public function widen($width, $callback = null) {
        $this->suffixWidth = $width;
        $this->suffixArray['widen'] = '';
        $this->triggers['widen'] = array($width, $callback);
        return $this;
    }

    /**
     * Build PSR-7 compatible StreamInterface with current image in given format and quality.
     *
     * @param string|null $format
     * @param int $quality
     * @return $this
     */
    public function stream($format = null, $quality = 90) {
        return $this->returnTrigger('stream', array($format, $quality));
    }

    /**
     * Build PSR-7 compatible ResponseInterface with current image in given format and quality.
     *
     * @param string|null $format
     * @param int $quality
     * @return $this
     */
    public function psrResponse($format = null, $quality = 90) {
        return $this->returnTrigger('psrResponse', array($format, $quality));
    }

    /**
     * Starts encoding of current image
     *
     * @param null $format
     * @param int $quality
     * @return $this
     */
    public function encode($format = null, $quality = null) {
        return $this->returnTrigger('encode', array($format, $quality));
    }

    /**
     * Checks if current image is already encoded
     *
     * @return boolean
     */
    public function isEncoded() {
        return $this->returnTrigger('isEncoded');
    }

    /**
     * Returns the current image in core format of the particular driver.
     * If you're using GD, you will get the the current GD resource as return value.
     * If you have setup the Imagick driver, the method will return the current image information as an Imagick object.
     *
     * @return mixed
     */
    public function getCore() {
        return $this->returnTrigger('getCore');
    }

    /**
     * Get file size
     *
     * @return mixed
     */
    public function filesize() {
        return $this->returnTrigger('filesize');
    }

    /**
     * Read MIME Type of current image instance, if it's already defined.
     *
     * @return mixed
     */
    public function mime() {
        return $this->returnTrigger('mime');
    }

    /**
     * Get fully qualified path to image
     *
     * @return string
     */
    public function basePath() {
        return $this->returnTrigger('basePath');
    }

    protected function setSuffix() {
        // Check Suffix
        if(is_null($this->_suffix)) {
            // Generate file suffix
            if(!is_null($this->suffix) && !is_array($this->suffix)) {
                $this->_suffix = $this->suffix;
            } elseif(!empty($this->suffixArray)) {
                foreach($this->suffixArray as $key => $sfx) {
                    if($sfx != "") $sfx = $sfx . $this->suffixSeparator;
                    $this->_suffix .= $sfx . $this->suffixes[$key];
                }
            } else {
                $this->_suffix = "";
            }
        }

        // Apply md5() suffix
        if(!is_null($this->config['md5-suffix']) && $this->config['md5-suffix'] === true) $this->_suffix = substr(md5($this->_suffix), 0, 12);

        $suffixStr = str_replace(
            array("{width}", "{height}", "{separator}", "{values}"),
            array($this->suffixWidth, $this->suffixHeight, $this->suffixSeparator, $this->_suffix),
            $this->suffixTemplate
        );

        $this->_suffix = $suffixStr;
    }

    protected function setTargetFile() {

        $this->targetFilename = $this->sourceFilename . $this->_suffix;
        $this->targetFileExtension = $this->sourceFileExtension;
        $this->targetFullFilename = $this->targetFilename . $this->targetFileExtension;

        $this->targetFilePath = $this->targetPath;
        $this->targetFileFullPath = $this->targetPath . $this->targetFullFilename;

        if(file_exists($this->targetFileFullPath)) $this->hasTarget = TRUE;
    }

    /**
     * This trigger working for return functions
     *
     * @param $function
     * @param array $args
     * @return mixed
     */
    protected function returnTrigger($function, $args = array()) {

        $this->beforeTrigger();

        if($this->hasTarget === FALSE) {
            $imageManager = new ImageManager(array(
                'driver' => $this->config['driver']
            ));

            $manager = $imageManager->make($this->image);
            return $this->callFunction($manager, $function, $args, true);
        }
    }

    /**
     * Trigger Runner
     *
     * @param $manager
     * @return string
     */
    protected function triggerRunner($manager) {
        if(is_array($this->triggers)) {
            foreach($this->triggers as $function => $args) {
                $manager = $this->callFunction($manager, $function, $args);
            }
        }
        return $manager;
    }

    /**
     * Made some check before Trigger Runner
     */
    protected function beforeTrigger() {
        // Set target File Suffix options
        if(is_null($this->_suffix)) $this->setSuffix();
        // Set Target File Options
        if(is_null($this->targetFileFullPath)) $this->setTargetFile();
        return;
    }

    /**
     * Call Given function with args
     *
     * @param $manager
     * @param $function
     * @param array $args
     * @param bool|false $return
     * @return string
     */
    protected function callFunction($manager, $function, $args=array(), $return=false) {
        switch (count($args)) {
            case 0:
                $rtrn = $manager->{$function}();
                break;
            case 1:
                $rtrn = $manager->{$function}($this->isNullIsFalse($args[0]));
                break;
            case 2:
                $rtrn = $manager->{$function}($this->isNullIsFalse($args[0]), $this->isNullIsFalse($args[1]));
                break;
            case 3:
                $rtrn = $manager->{$function}($this->isNullIsFalse($args[0]), $this->isNullIsFalse($args[1]), $this->isNullIsFalse($args[2]));
                break;
            case 4:
                $rtrn = $manager->{$function}($this->isNullIsFalse($args[0]), $this->isNullIsFalse($args[1]), $this->isNullIsFalse($args[2]), $this->isNullIsFalse($args[3]));
                break;
            case 5:
                $rtrn = $manager->{$function}($this->isNullIsFalse($args[0]), $this->isNullIsFalse($args[1]), $this->isNullIsFalse($args[2]), $this->isNullIsFalse($args[3]), $this->isNullIsFalse($args[4]));
                break;
            default: $rtrn = call_user_func_array(array($manager, $function), $args); break;
        }
        if($return === true) return $rtrn;
    }

    /**
     * Check value is null or bool and return result
     *
     * @param $value
     * @return bool|null
     */
    function isNullIsFalse($value) {
        if(is_null($value)) return null;
        if($value === false) return false;
        return $value;
    }

    /**
     * Saves encoded image in filesystem
     *
     * @param null $quality
     * @return array|null
     */
    public function save($quality = null) {
        $this->beforeTrigger();
        // If target file not exist, create target file
        if($this->hasTarget === FALSE) {
            $manager = $this->returnTrigger('save', array($this->targetFileFullPath, $quality));
            if(!is_null($this->pageImage)) return $this->setProcessWireImage($manager);
            return $this->targetFileFullPath;
        }
        if(!is_null($this->pageImage)) return $this->setProcessWireImage();
        else return $this->targetFileFullPath;
    }

    protected function setProcessWireImage($manager=NULL, $debug=false) {
        if($debug === true) {
            echo "<pre>" . print_r(array(
                    '_suffix' => $this->_suffix,
                    'image' => $this->image,
                    'targetFileName' => $this->targetFilename,
                    'targetFileExtension' => $this->targetFileExtension,
                    'targetFullFilename' => $this->targetFullFilename,
                    'targetFilePath' => $this->targetFilePath,
                    'targetFileFullPath' => $this->targetFileFullPath
                ), true) . "</pre>";
        }

        $pageimage = clone $this->pageImage;
        $pageimage->setFilename($this->targetFileFullPath);
        $pageimage->setOriginal($this->pageImage);
        wireChmod($this->targetFileFullPath);
        if(!is_null($manager)) $manager->destroy();
        return $pageimage;
    }
}