<?php

namespace filamentv\app\extensions\editors\tinymce\assets;

use yii\web\AssetBundle;

/**
 * Class Asset
 * 
 * @package filamentv\app\extensions\editors\tinymce\assets
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */
class Asset extends AssetBundle {

    public $depends = [
        \yii\web\JqueryAsset::class,
        \yii\web\YiiAsset::class,
        AssetTinymce::class,
        AssetLang::class,
    ];

}
