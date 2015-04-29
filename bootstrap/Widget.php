<?php

namespace filamentv\app\bootstrap;

use Yii;

/**
 * Class Widget
 * 
 * @package filamentv\app\bootstrap
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 19/03/2015
 */
abstract class Widget extends \yii\bootstrap\Widget {

    /**
     * View's file name
     * @var string
     */
    public $view = 'Widget';

    /**
     * Widget's name
     * @var string
     */
    public $name = 'widget';

    /**
     * Path to messages
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
     * Messages transplate register
     *  /<lang>/messages/app.php
     * 
     * uses:
     * Yii::t('widget-search', 'messages')
     */
    public function registerTranslations() {

        Yii::$app->i18n->translations['widget-' . $this->name] = [
            'class' => \yii\i18n\PhpMessageSource::class,
            'basePath' => $this->translationsBasePath,
            'fileMap' => [
                'widget-' . $this->name => 'app.php',
            ],
        ];
    }

    /**
     * @inherit
     */
    public function run() {
        return $this->render($this->view);
    }

}
