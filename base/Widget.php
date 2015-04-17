<?php

namespace filamentv\app\base;

/**
 * Class Widget
 * 
 * @package thread\base
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 19/03/2015
 */
abstract class Widget extends \yii\base\Widget {

    /**
     * Назва файлу відображення
     * @var string
     */
    public $view = 'Widget';

    /**
     * Назва віджету
     * @var string
     */
    public $name = 'widget';

    /**
     * Шлях до каталогу з повідомленнями
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
     * Підключення перекладів.
     * В каталозі має бути створено файл перекладу <lang>/messages/app.php
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
