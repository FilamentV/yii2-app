<?php

namespace filamentv\app\base;

use Yii;

/**
 * Class Module
 *
 * @package filamentv\app\base
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */
abstract class Module extends \yii\base\Module {

    /**
     * Model's name
     * @var string
     */
    public $title = "Module";

    /**
     * Recors on page
     * @var integer
     */
    public $itemOnPage = 50;

    /**
     * The base directory for saving files
     * @var string
     */
    public $fileUploadFolder = "";

    /**
     * Model's name for translations extentions
     * @var string
     */
    public $name = 'module';

    /**
     * Path to files with translations messages
     * @var string
     */
    public $translationsBasePath = '@app/messages';

    /**
     * Path to file configuration
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
     *
     * uses:
     * Yii::t('module', 'messages')
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
     * Database's connect return
     */
    public static function getDb() {
        return Yii::$app->get('db');
    }

}
