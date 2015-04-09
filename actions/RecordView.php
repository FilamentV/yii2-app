<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class RecordView
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 *
  public function actions() {
  return [
 * ...
  'view' => [
  'class' => RecordView::class,
  'query' => Model::findOne(),
  ],
 * ...
  ];
  }
 *
 */
class RecordView extends Action {

    /**
     * The base layout
     * @var string
     */
    public $layout = '@app/layouts/column1';

    /**
     * The view file
     * @var string
     */
    public $view = 'view';

    /**
     * @var string ActiveRecord::class
     */
    public $modelClass = null;

    /**
     * The name method into model
     * @var string
     */
    public $methodName = null;

    /**
     * @var boollean false|true
     */
    public $checkAccess = false;

    /**
     * @var ActiveRecord
     */
    protected $model = null;

    /**
     * @inheritdoc
     */
    public function init() {
        if ($this->modelClass === null)
            throw new Exception(__CLASS__ . '::$modelClass must be set.');

        $this->model = new $this->modelClass;

        if ($this->model === null)
            throw new Exception($this->modelClass . ' must be exists.');

        if (!method_exists($this->model, $this->methodName))
            throw new Exception($this->modelClass . '::' . $this->methodName . ' must be exists.');
    }

    /**
     * @inheritdoc
     */
    public function run($alias) {

        $ref = new \ReflectionMethod($this->model, $this->methodName);
        $model = $ref->invoke($this->model, $alias);

        if ($model === null)
            throw new NotFoundHttpException;

        if (Yii::$app->getRequest()->isAjax) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $this->controller->renderPartial($this->view, [
                        'model' => $model,
            ]);
        } else {
            $this->controller->layout = $this->layout;

            return $this->controller->render($this->view, [
                        'model' => $model,
            ]);
        }
    }

}
