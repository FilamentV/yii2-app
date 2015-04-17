<?php

namespace filamentv\app\base;

use Yii;

/**
 * Class Module
 *
 * @package thread\base
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 19/03/2015
 */
abstract class Module extends \yii\base\Module {

    /**
     * Назва модуля
     * @var string
     */
    public $title = "Module";

    /**
     * Кількість записів на сторінку
     * @var integer
     */
    public $itemOnPage = 50;

    /**
     * Базова директорія для збереження файлів
     * @var string
     */
    public $fileUploadFolder = "";

    /**
     * Назва віджету
     * @var string
     */
    public $name = 'module';

    /**
     * Шлях до каталогу з повідомленнями
     * @var string
     */
    public $translationsBasePath = '@app/messages';

    /**
     * Шлях до файлу конфігурації
     * @var string
     */
    public $configPath = '@app/modules/config.php';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        Yii::configure($this, require(Yii::getAlias($this->configPath)));
        $this->registerTranslations();
    }

    /**
     * Registers translations
     */
    public function registerTranslations() {

        Yii::$app->i18n->translations[$this->name] = [
            'class' => \yii\i18n\PhpMessageSource::class,
            'basePath' => $this->translationsBasePath,
            'fileMap' => [
                $this->name => 'app.php',
            ],
        ];
    }

    /**
     * Повертає об'єкт підключення до БД
     */
    public static function getDb() {
        return Yii::$app->get('db');
    }

}
