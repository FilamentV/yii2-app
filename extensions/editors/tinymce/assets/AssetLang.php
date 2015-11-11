<?php

namespace filamentv\app\extensions\editors\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Class AssetLang
 * 
 * @package thread\extensions\editors\tinymce\assets
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */

class AssetLang extends AssetBundle {

    public $sourcePath = __DIR__;
    public $js = [
        'langs/uk-UA.js',
    ];

}
