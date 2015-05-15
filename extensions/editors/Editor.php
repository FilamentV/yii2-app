<?php

namespace filamentv\app\extensions\editors;

use Yii;
use yii\widgets\InputWidget;

/**
 * Class Editor
 *
 * @package filamentv\app\extensions\editors
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 18/03/2015
 */
class Editor extends InputWidget {

    /**
     * Editor type's set
     * @var string tinymce
     */
    public $editor;

    /**
     * Editor's thema
     * @var string
     */
    public $thema;

    /**
     * Editor's height window
     * @var type
     */
    public $height;

    /**
     * Editor by default
     * @var string
     */
    private $_defaultEditor = 'Tinymce';

    /**
     * The list of allowed editors
     *
     * @var arrray
     */
    private $_editors = [
        'Tinymce' => \filamentv\app\extensions\editors\tinymce\Tinymce::class,
        'Textarea' => \filamentv\app\extensions\editors\textarea\Textarea::class,
    ];
    public $language = '';

    /**
     * @inheritdoc
     */
    public function init() {

        if (empty($this->language))
            $this->language = Yii::$app->language;

        if (!in_array($this->editor, $this->_editors))
            $this->editor = $this->_defaultEditor;
    }

    /**
     * @inheritdoc
     */
    public function run() {
        $e = $this->_editors[$this->editor];
        return $e::widget([
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'thema' => $this->thema,
                    'language' => $this->language,
        ]);
    }

}
