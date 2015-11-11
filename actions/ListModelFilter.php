<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Exception;

/**
 * Class ListModelFilter
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 *
 */
class ListModelFilter extends \yii\base\Component {

    /**
     * The view file
     * @var string
     */
    public $view = '_filter';

    /**
     * @var string ActiveRecord::class
     */
    public $modelClass = null;

    /**
     *
     * @var string get|post by default get
     */
    public $requestMethod = 'get';

    /**
     *
     * @var string
     */
    public $controller = null;

    /**
     * @var ActiveRecord
     */
    protected $model = null;

    /**
     * 
     * @throws Exception
     */
    public function init() {

        if ($this->modelClass === null && class_exists($this->modelClass)) {
            throw new Exception(__CLASS__ . '::$modelClass must be set and exists.');
        }

        $this->model = new $this->modelClass;

        if ($this->controller !== null && !($this->controller instanceof \yii\base\Controller)) {
            throw new Exception(__CLASS__ . '::$controllerClass must be set and exists.');
        }
    }

    /**
     * 
     * @return array|string
     */
    public function run() {

        $data = ($this->requestMethod === 'post') ? Yii::$app->getRequest()->post('Article') : Yii::$app->getRequest()->get('Article');

        $this->model->setAttributes($data);
        $this->model->validate();

        if ($this->controller === null) {
            return [
                'view' => $this->view,
                'model' => $this->model,
            ];
        } else {
            return $this->controller->renderPartial($this->view, [
                        'model' => $this->model,
            ]);
        }
    }

}
