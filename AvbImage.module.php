<?php
/**
 * Class AvbImage
 *
 *
 * @author          : İskender TOTOĞLU, @ukyo (community), @trk (Github)
 * @website         : http://altivebir.com.tr
 * @projectWebsite  : https://github.com/trk/AvbImage
 */
class AvbImage extends WireData implements Module, ConfigurableModule {

    /**
     * getModuleInfo is a module required by all modules to tell ProcessWire about them
     *
     * @return array
     *
     */
    public static function getModuleInfo() {
        return array(
            'title' => 'AvbImage',
            'summary' => 'Image Manipulation Module for ProcessWire',
            'version' => 1,
            'author' => 'İskender TOTOĞLU | @ukyo(community), @trk (Github), http://altivebir.com.tr',
            'href' => 'https://github.com/trk/AvbImage',
            'icon' => 'check-square-o',
            'singular' => true,
            'autoload' => true,
            'requires' => 'ProcessWire>=2.5.11'
        );
    }

    /**
     * Default AvbFastCache Modules Configurations
     *
     * @return array
     */
    static public function getDefaultData() {
        return array(
            'driver' => 'gd',
            'md5Suffix' => 1,
            'suffixSeparator' => '-'
        );
    }

    public function __construct(){
        foreach(self::getDefaultData() as $key => $value) $this->$key = $value;
    }

    /**
     * Initialize the module and setup hooks
     */
    public function init() {
        $this->addHook('Pageimage::image', $this, 'AvbImageManipulator');
    }

    public function AvbImageManipulator($event) {
        $pageImage = $event->object;

        $driver = $this->driver;
        $md5Suffix = $this->md5Suffix;
        $suffix = NULL;
        $suffixSeparator = $this->suffixSeparator;

        if(!empty($event->arguments[0])) {
            $arguments = $event->arguments[0];
            if(array_key_exists('driver', $arguments)) {
                if($arguments['driver'] == 'gd' || $arguments['driver'] == 'imagick') {
                    $driver = $arguments['driver'];
                }
            }
            if(array_key_exists('md5-suffix', $arguments)) {
                $md5Suffix = $arguments['md5-suffix'];
            }
            if(array_key_exists('suffix', $arguments)) {
                $suffix = $arguments['suffix'];
            }
            if(array_key_exists('suffix-separator', $arguments)) {
                $suffixSeparator = $arguments['suffix-separator'];
            }
        }

        if(!class_exists('AvbImageManager')) {
            require_once(__DIR__ . '/AvbImageManager.php');
        }
        $AvbImageManager = new AvbImageManager(array(
            'driver' => $driver,
            'pageImage' => $pageImage,
            'suffix' => $suffix,
            'md5-suffix' => ($md5Suffix === 1 || $md5Suffix === true) ? true : false,
            'suffix-separator' => $suffixSeparator
        ));

        $event->return = $AvbImageManager;
    }

    /**
     * Use this for external image manipulation
     *
     * @param array $config
     * @return AvbImageManager
     */
    public function image($sourceImage, $config = array()) {

        $driver = $this->driver;
        $md5Suffix = $this->md5Suffix;
        $suffix = NULL;
        $suffixSeparator = $this->suffixSeparator;

        if(!empty($config)) {
            if(array_key_exists('driver', $config)) {
                if($config['driver'] == 'gd' || $config['driver'] == 'imagick') {
                    $driver = $config['driver'];
                }
            }
            if(array_key_exists('md5-suffix', $config)) {
                $md5Suffix = $config['md5-suffix'];
            }
            if(array_key_exists('suffix', $config)) {
                $suffix = $config['suffix'];
            }
            if(array_key_exists('suffix-separator', $config)) {
                $suffixSeparator = $config['suffix-separator'];
            }
        }

        return new AvbImageManager(array(
            'driver' => $driver,
            'sourceImage' => $sourceImage,
            'suffix' => $suffix,
            'md5-suffix' => ($md5Suffix === 1 || $md5Suffix === true) ? true : false,
            'suffix-separator' => $suffixSeparator
        ));
    }

    /**
     * Configure the AvbFastCache Module
     */
    public static function getModuleConfigInputfields(array $data) {

        $fields = new InputfieldWrapper();
        $modules = wire('modules');
        $data = array_merge(self::getDefaultData(), $data);

        // Cache Storage Type
        $fieldName = "driver";
        $f = $modules->get('InputfieldSelect');
        $f->attr('name', $fieldName);
        $f->label = __("Image Manipulator Driver");
        $f->description = __("GD or Imagick");
        $f->required = true;
        $f->addOptions(array(
            'gd' => 'GD',
            'imagick' => 'Imagick'
        ));
        $f->value = $data[$fieldName];
        $fields->add($f);

        // Option : Apply md5() for suffix
        $fieldName = "md5Suffix";
        $value = empty($data[$fieldName]) ? '' : 'checked';
        $f = $modules->get('InputfieldCheckbox');
        $f->label = __("Use md5() for suffix");
        $f->attr('name', $fieldName);
        $f->attr('checked',$value );
        $fields->add($f);

        // Config : Suffix Separator
        $fieldName = "suffixSeparator";
        $f = $modules->get('InputfieldText');
        $f->attr('name', $fieldName);
        $f->attr('value', $data[$fieldName]);
        $f->label = __("Suffix Separator");
        $f->required = true;
        $fields->add($f);

        return $fields;
    }
}