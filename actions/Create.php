<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Exception;
use yii\log\Logger;
use filamentv\app\base\ActionCRUD;

/**
 * Class Create
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 *
  public function actions() {
  return [
 * ...
  'create' => [
  'class' => Create::class,
  'modelClass' => Model::class,
  ],
 * ...
  ];
  }
 *
 */
class Create extends ActionCRUD {

    /**
     * 
     * @throws Exception
     */
    public function init() {
        if ($this->modelClass === null){
            throw new Exception(__CLASS__ . '::$modelClass must be set.');
        }

        $this->model = new $this->modelClass;
        $this->model->loadDefaultValues();

        if ($this->model === null){
            throw new Exception($this->modelClass . 'must be exists.');
        }

        if (!$this->model->is_scenario($this->scenario)){
            throw new Exception($this->modelClass . '::' . $this->scenario . ' scenario doesn\'t exist');
        }
    }

    /**
     * 
     * @return type
     */
    public function run() {

        if (Yii::$app->getRequest()->isAjax) {
            return $this->controller->renderPartial($this->view, [
                        'model' => $this->model,
            ]);
        } else {
            if ($this->saveModel()) {
                return $this->controller->redirect($this->getRedirect());
            } else {

                $this->controller->layout = $this->layout;

                return $this->controller->render($this->view, [
                            'model' => $this->model,
                ]);
            }
        }
    }

    /**
     * Save data into $model
     * Default scenario is 'backend'
     * @return boolean
     */
    public function saveModel() {
        $save = false;
        $this->model->setScenario($this->scenario);

        if ($this->model->load(Yii::$app->getRequest()->post())) {
            $model = $this->model;
            $transaction = $model::getDb()->beginTransaction();
            try {
                $save = $this->model->save();

                ($save) ? $transaction->commit() : $transaction->rollBack();
            } catch (Exception $e) {
                $this->toLog($e->getMessage(), Logger::LEVEL_ERROR);
                $transaction->rollBack();
            }
        }

        return $save;
    }

}
