<?php

namespace filamentv\app\extensions\editors\textarea;

use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class Textarea
 * 
 * @package filamentv\app\extensions\editors\textarea
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 18/03/2015
 */
final class Textarea extends InputWidget {

    /**
     * Налаштування 
     * @var array
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
     * @var yii\helpers\Html textarea
     */
    private $_textarea;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();

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
    }

    /**
     * @inheritdoc
     */
    public function run() {
        if ($this->_textarea !== null)
            echo $this->_textarea;
    }

}
