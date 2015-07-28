<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\log\Logger;
use filamentv\app\base\ActionCRUD;

/**
 * Class Delete
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 *
  public function actions() {
  return [
 * ...
  'delete' => [
  'class' => Delete::class,
  'modelClass' => Model::class,
  'attribute' => 'delete'
  ],
 * ...
  ];
  }
 *
 */
class Delete extends ActionCRUD {

    /**
     * it's redirect 
     * @var type array|string| typeof Closure
     */
    public $redirect = ['list'];

    /**
     * 
     * @throws Exception
     */
    public function init() {

        if ($this->modelClass === null)
            throw new Exception(__CLASS__ . '::$modelClass must be set.');

        $this->model = new $this->modelClass;

        if ($this->model === null)
            throw new Exception($this->modelClass . '::$modelClass must be set.');
    }

    /**
     * 
     * @param type $id
     * @return type
     */
    public function run($id) {

        $delete = false;

        if ($model = $this->findModel($id)) {

            $transaction = $model::getDb()->beginTransaction();

            try {
                $delete = $model->delete();
                ($delete) ? $transaction->commit() : $transaction->rollBack();
            } catch (Exception $e) {
                Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
                $transaction->rollBack();
            }
        }

        if (Yii::$app->getRequest()->isAjax) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $delete;
        } else {
            $this->controller->redirect($this->getRedirect());
        }
    }

}
