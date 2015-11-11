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
 */
class Textarea extends InputWidget {

    /**
     * Settings
     * @var array
     */
    public $settings = [];

    /**
     * Call settings for the editor
     * @var string ''|full|mini
     */
    public $thema = '';

    /**
     * Language editor
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

        //Add a field to the editor and define ID
        if (!isset($this->settings['selector'])) {
            $this->settings['selector'] = '#' . $this->options['id'];

            $this->_textarea = ($this->hasModel()) ?
                    Html::activeTextarea($this->model, $this->attribute, $this->options) :
                    Html::textarea($this->name, $this->value, $this->options);
        }
        /* IF [[options['selector']]] false remove from the setting. */
        if (isset($this->settings['selector']) && $this->settings['selector'] === false) {
            unset($this->settings['selector']);
        }
    }

    /**
     * @inheritdoc
     */
    public function run() {
        if ($this->_textarea !== null) {
            return $this->_textarea;
        }
    }

}
