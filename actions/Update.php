<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Exception;
use filamentv\app\base\ActionCRUD;

/**
 * Class Update
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 *
  public function actions() {
  return [
 * ...
  'create' => [
  'class' => Update::class,
  'modelClass' => Model::class,
  ],
 * ...
  ];
  }
 *
 */
class Update extends ActionCRUD {

    /**
     * @inheritdoc
     */
    public function init() {
        if ($this->modelClass === null)
            throw new Exception(__CLASS__ . '::$modelClass must be set.');

        $this->model = new $this->modelClass;

        if ($this->model === null)
            throw new Exception($this->modelClass . 'must be exists.');

        if (!$this->model->is_scenario($this->scenario))
            throw new Exception($this->modelClass . '::' . $this->scenario . ' scenario doesn\'t exist');
    }

    /**
     * @inheritdoc
     */
    public function run($id) {

        if ($this->model === null)
            throw new Exception($this->modelClass . 'must be exists.');

        if (Yii::$app->getRequest()->isAjax) {
            return $this->controller->renderPartial($this->view, [
                        'model' => $this->model,
            ]);
        } else {

            if ($this->saveModel($id)) {
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
    public function saveModel($id) {
        $save = false;
        $this->model = $this->findModel($id);
        $this->model->setScenario($this->scenario);

        if ($this->model->load(Yii::$app->getRequest()->post())) {
            $model = $this->model;
            $transaction = $model::getDb()->beginTransaction();
            try {
                $save = $this->model->save();

                ($save) ? $transaction->commit() : $transaction->rollBack();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $save;
    }

}
