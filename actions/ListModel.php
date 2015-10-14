<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\base\Exception;
use filamentv\app\actions\ListModelFilter;

/**
 * Class ListModel
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 *
  public function actions() {
  return [
 * ...
  'list' => [
  'class' => DataProviderList::class,
  'modelClass' => Model::class,
  'methodName' => 'search',
  ],
 * ...
  ];
  }
 *
 */
class ListModel extends Action {

    /**
     * The base layout
     * @var string
     */
    public $layout = '@app/layouts/list';

    /**
     * The view file
     * @var string
     */
    public $view = 'list';

    /**
     *
     * @var array 
     */
    public $filter = [
        'class' => ListModelFilter::class,
        'modelClass' => null,
    ];

    /**
     * @var string ActiveRecord::class
     */
    public $modelClass = null;

    /**
     * The name method into model
     * @var string
     */
    public $methodName = 'search';

    /**
     * @var boollean false|true
     */
    public $checkAccess = false;

    /**
     * @var ActiveRecord
     */
    protected $model = null;

    /**
     * @var Object
     */
    protected $_filter = null;

    /**
     * 
     * @throws Exception
     */
    public function init() {
        if ($this->modelClass === null && class_exists($this->modelClass))
            throw new Exception(__CLASS__ . '::$modelClass must be set and exists.');

        $this->model = new $this->modelClass;

        if (!method_exists($this->model, $this->methodName))
            throw new Exception($this->modelClass . '::' . $this->methodName . ' must be exists.');

        /**
         * Create Filter if exists params
         */
        if ($this->filter['class'] !== null && class_exists($this->filter['class']) && $this->filter['modelClass'] !== null) {

            $f = $this->filter;
            $f['controller'] = $this->controller;
            unset($f['class']);

            $this->_filter = new $this->filter['class']($f);
        }
    }

    /**
     * 
     * @return type
     */
    public function run() {

        $this->controller->layout = $this->layout;

        if (Yii::$app->getRequest()->isAjax) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            return $this->controller->renderPartial($this->view, [
                        'model' => $this->model,
            ]);
        } else {
            $this->controller->layout = $this->layout;

            return $this->controller->render($this->view, [
                        'model' => $this->model,
            ]);
        }
    }

    /**
     * 
     * @return Filter|null
     */
    public function getFilter() {
        return $this->_filter;
    }

}
