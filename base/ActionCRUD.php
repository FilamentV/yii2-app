<?php

namespace filamentv\app\base;

use yii\base\Action;

/**
 * Class ActionCRUD
 *
 * @package filamentv\app\base
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 */
class ActionCRUD extends Action {

    /**
     * layout's file
     * @var string
     */
    public $layout = '@app/layouts/crud';

    /**
     * View's file
     * @var string
     */
    public $view = '_form';

    /**
     * \yii\db\ActiveRecord
     * @var string
     */
    public $modelClass = null;

    /**
     * \yii\db\ActiveRecord
     * @var string
     */
    public $modelClassLang = null;

    /**
     * Redirect
     * @var string|array| typeof Closure
     */
    public $redirect = 'update';

    /**
     * model's scenario
     * @var string
     */
    public $scenario = 'backend';

    /**
     * Check for permission
     * @var boollean false|true
     */
    public $checkAccess = false;

    /**
     * @var ActiveRecord
     */
    protected $model = null;

    /**
     * @var ActiveRecord
     */
    protected $modelLang = null;

    /**
     * Search by model pervychnomu key
     * If the pattern is not found, returns null
     * @param integer|array $id Ідентифікатор моделі
     * @return {Model}|null Повернення знайденої моделі
     */
    public function findModel($id) {

        $modelClass = $this->model;
        $keys = $modelClass::primaryKey();

        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        return $model;
    }

    /**
     * Search by model pervychnomu key
     * If the pattern is not found, returns null
     * @param integer $id Ідентифікатор моделі
     * @return {Model}Lang Повернення знайденої моделі
     */
    protected function findModelLang($id) {

        if ($id) {
            $model = $this->modelLang->find()->andWhere(['rid' => $id])->one();
        }

        if ($model === null) {
            $model = $this->modelLang->loadDefaultValues();
        }

        return $model;
    }

    /**
     * Generates a link where we return after storage
     * @return type
     */
    public function getRedirect() {

        $redirect = $this->redirect;
        if (is_array($redirect)) {
            $r = $redirect;
        } elseif ($redirect instanceof \Closure) {
            $r = $redirect();
        } else {
            $r = [$this->redirect, 'id' => $this->model->id];
        }
        return $r;
    }

    /**
     * Returns the data model that is created in the process
     * @return object ActiveRecord
     */
    public function getModel() {
        return $this->model;
    }

}
