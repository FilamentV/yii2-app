<?php

namespace filamentv\app\base;

use Yii;

/**
 * Class Widget
 *
 * @package filamentv\app\base
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 19/03/2015
 */
abstract class Widget extends \yii\base\Widget {

    /**
     * View's file
     * @var string
     */
    public $view = 'Widget';

    /**
     * Widget's name
     * @var string
     */
    public $name = 'widget';

    /**
     * Path to files with translations messages
     * @var string
     */
    public $translationsBasePath = '@app/messages';

    /**
     * @inherit
     */
    public function init() {
        parent::init();

        $this->registerTranslations();
    }

    /**
     * File translation include
     * <lang>/messages/app.php
     */
    public function registerTranslations() {

        Yii::$app->i18n->translations['widget-' . $this->name] = [
            'class' => yii\i18n\PhpMessageSource::class,
            'basePath' => $this->translationsBasePath,
            'fileMap' => [
                'widget-' . $this->name => 'app.php',
            ],
        ];
    }

}
