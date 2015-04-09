<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Exception;
use yii\web\Response;
use filamentv\app\base\ActionCRUD;

/**
 * Class AttributeSave
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 *
  public function actions() {
  return [
 * ...
  'published' => [
  'class' => AttributeSave::class,
  'modelClass' => Model::class,
  'attribute' => 'published'
  ],
 * ...
  ];
  }
 *
 */
class AttributeSave extends ActionCRUD {

    /**
     * it's attribute model
     * @var string
     */
    public $attribute;

    /**
     * it's redirect 
     * @var type array|string| typeof Closure
     */
    public $redirect = ['list'];

    /**
     * @inheritdoc
     */
    public function init() {

        if ($this->modelClass === null)
            throw new Exception(__CLASS__ . '::$modelClass must be set.');

        $this->model = new $this->modelClass;

        if ($this->model === null)
            throw new Exception($this->modelClass . 'must be exists.');

        if (!$this->model->is_attribute($this->attribute))
            throw new Exception($this->modelClass . '::' . $this->attribute . ' attribute doesn\'t exist');

        if (!$this->model->is_scenario($this->attribute))
            throw new Exception($this->modelClass . '::' . $this->attribute . ' scenario doesn\'t exist');
    }

    /**
     * 
     * @param type $id
     * @param type $value
     * @return type
     */
    public function run($id, $value) {

        $save = $this->save($id, $value);

        if (Yii::$app->getRequest()->isAjax) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $save;
        } else {
            $this->controller->redirect($this->getRedirect());
        }
    }

    /**
     * Save data into $model
     * @param integer $id
     * @param mixed $value
     * @return boollean
     */
    protected function save($id, $value) {

        $save = false;

        if ($model = $this->findModel($id)) {
            $model->setScenario($this->attribute);

            $model->{$this->attribute} = $value;

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $save = $model->save();
                ($save) ? $transaction->commit() : $transaction->rollBack();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $save;
    }

}
