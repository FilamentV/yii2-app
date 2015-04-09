<?php

namespace filamentv\app\extensions\editors\tinymce;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use yii\helpers\ArrayHelper;
use filamentv\app\extensions\editors\tinymce\assets\Asset;
use filamentv\app\extensions\editors\tinymce\assets\AssetLang;

/**
 * Class Tinymce
 * 
 * @package filamentv\app\extensions\editors\tinymce
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 18/03/2015
 */
final class Tinymce extends InputWidget {

    /**
     * Налаштування редактору
     * @var array {@link http://www.tinymce.com/wiki.php/Configuration redactor options}.
     */
    public $settings = [];

    /**
     * Виклик налаштувань для редактору
     * @var string ''|full|mini 
     */
    public $thema = '';

    /**
     * Мова інтерфейсу редатору
     * @var string 
     */
    public $language;

    /**
     * Посилання на файл, що містить переводи інтерфейсу мови
     * @var string link 
     */
    protected $language_url;

    /**
     * Налаштування редактору за замовчуванням
     * @var array
     */
    protected $_defaultSettings;

    /**
     * @var yii\helpers\Html textarea
     */
    private $_textarea;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

        switch ($this->thema) {
            case 'mini' : $this->getMiniSetting();
                break;
            case 'full' : $this->getFullSetting();
                break;
            default : $this->getDefaultSetting();
                break;
        }

        //Додаємо поле для редактору та визначаємо ідентифікатор
        if (!isset($this->settings['selector'])) {
            $this->settings['selector'] = '#' . $this->options['id'];

            $this->_textarea = ($this->hasModel()) ?
                    Html::activeTextarea($this->model, $this->attribute, $this->options) :
                    Html::textarea($this->name, $this->value, $this->options);
        }
        /* Якщо [[options['selector']]] false видаляємо з налаштувань. */
        if (isset($this->settings['selector']) && $this->settings['selector'] === false) {
            unset($this->settings['selector']);
        }

        if (empty($this->language))
            $this->language = Yii::$app->language;

        $this->settings = ArrayHelper::merge($this->_defaultSettings, $this->settings);
    }

    /**
     * @inheritdoc
     */
    public function run() {
        if ($this->_textarea !== null) {
            $this->registerClientScript();
            echo $this->_textarea;
        }
    }

    /**
     * Регистрируем AssetBundle-ы виджета.
     */
    public function registerClientScript() {
        $view = $this->getView();
        Asset::register($view);
        $assetslang = AssetLang::register($view);
        $this->language_url = $a->baseUrl . '/' .
                $assetslang->js[0];
        $this->settings['language_url'] = $this->language_url;

        $settings = Json::encode($this->settings);

        $view->registerJs("tinymce.init($settings);");
    }

    protected function getDefaultSetting() {
        $this->_defaultSettings = [
            'language' => $this->language,
            'language_url' => '',
            'relative_urls' => false,
            'height' => '200px',
            'menubar' => true,
            'statusbar' => false,
            'plugins' => ['advlist autolink link image lists hr table'],
            'toolbar' => 'bold italic underline strikethrough | bullist numlist | link unlink image | hr table blockquote | pagebreak'
        ];
    }

    protected function getMiniSetting() {
        $this->_defaultSettings = [
            'language' => $this->language,
            'relative_urls' => false,
            'language_url' => '',
            'height' => '150px',
            'menubar' => false,
            'statusbar' => false,
            'plugins' => [''],
            'toolbar' => 'bold italic underline strikethrough | bullist numlist | hr table'
        ];
    }

    protected function getFullSetting() {
        $this->_defaultSettings = [
            'language' => $this->language,
            'relative_urls' => false,
            'language_url' => '',
            'height' => '600px',
            'menubar' => true,
            'statusbar' => true,
            'plugins' => ['advlist autolink link image lists hr table pagebreak code'],
            'toolbar' => 'bold italic underline strikethrough | bullist numlist | link unlink image | hr table blockquote | pagebreak code'
        ];
    }

}
