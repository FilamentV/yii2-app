<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\helpers\Json;
use filamentv\app\base\ActionCRUD;

/**
 * Class Sorter
 *
 * @package thread\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 *
  public function actions() {
  return [
 * ...
  'sorter' => [
  'class' => AttributeSave::class,
  'modelClass' => Model::class,
  ],
 * ...
  ];
  }
 *
 */
class Sorter extends ActionCRUD {

    /**
     * Name of attribute which use to send data between frontend and backend
     * @var string
     */
    public $dataKey = 'sort';

    /**
     * taken data
     * 
     * @var array
     */
    public $data = [];

    /**
     * Model Attribute
     * @var string
     */
    public $attribute = 'position';

    /**
     * 
     * @throws Exception
     */
    public function init() {

        if (empty($this->dataKey)) {
            throw new Exception(__CLASS__ . '::$dataKey must be set.');
        }

        if ($this->modelClass === null) {
            throw new Exception(__CLASS__ . '::$modelClass must be set.');
        }

        $this->model = new $this->modelClass;

        if ($this->model === null) {
            throw new Exception($this->modelClass . 'must be exists.');
        }

        if (!$this->model->is_attribute($this->attribute)) {
            throw new Exception($this->modelClass . '::' . $this->attribute . ' attribute doesn\'t exist');
        }

        if (!$this->model->is_scenario($this->attribute)) {
            throw new Exception($this->modelClass . '::' . $this->attribute . ' scenario doesn\'t exist');
        }
    }

    /**
     * 
     * @return type
     */
    public function run() {
        //Load Data
        $this->data = Yii::$app->getRequest()->post($this->dataKey, []);
        if (!empty($this->data)) {
            $this->data = Json::decode($this->data);
        } else {
            $this->toLog('Data is empty');
        }

        //Save Data
        $save = (!empty($this->data)) ? $this->save() : false;

        if (Yii::$app->getRequest()->isAjax) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $save;
        } else {
            $this->controller->redirect($this->getRedirect());
        }
    }

    /**
     * Зберігає дані моделі
     * @return boollean
     */
    protected function save() {

        $save = false;
        $model = $this->model;
        $attribute = $this->attribute;

        $data = $this->data;
        $list = $model::find()->rangeID($data)->indexBy('id')->all();

        if ($list !== null) {

            $transaction = $model::getDb()->beginTransaction();

            try {

                foreach ($data as $key => $m) {
                    $list[$m][$attribute] = $key;
                    $list[$m]['scenario'] = $attribute;
                    $save = $list[$m]->save();
                    if ($save === false) {
                        break;
                    }
                }

                ($save) ? $transaction->commit() : $transaction->rollBack();
            } catch (Exception $e) {
                $this->toLog($e->getMessage(), Logger::LEVEL_ERROR);
                $transaction->rollBack();
            }
        }

        return $save;
    }

}
