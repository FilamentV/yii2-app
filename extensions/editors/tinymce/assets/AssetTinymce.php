<?php

namespace filamentv\app\extensions\editors\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Class AssetTinymce
 * 
 * @package filamentv\app\extensions\editors\tinymce\assets
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 23/03/2015
 */
class AssetTinymce extends AssetBundle {

    public $sourcePath = '@vendor/tinymce/tinymce';
    public $js = [
        'tinymce.jquery.min.js',
    ];

}
