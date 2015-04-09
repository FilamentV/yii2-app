<?php

namespace filamentv\app\base;

use yii\base\Action;

/**
 * Class ActionCRUD
 *
 * @package thred\base
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 */
class ActionCRUD extends Action {

    /**
     * Базовий layout
     * @var string
     */
    public $layout = '@app/layouts/crud';

    /**
     * Відображення
     * @var string
     */
    public $view = '_form';

    /**
     * Назва моделі з якою будемо працювати
     * модель має наслідувати \yii\db\ActiveRecord
     * @var string
     */
    public $modelClass = null;

    /**
     * Назва моделі з якою будемо працювати
     * модель має наслідувати \yii\db\ActiveRecordLang
     * @var string
     */
    public $modelClassLang = null;

    /**
     * Посилання переходу
     * @var string|array| typeof Closure
     */
    public $redirect = 'update';

    /**
     * Назва сценарію, що має виконуватися для валідації даних моделі
     * @var string
     */
    public $scenario = 'backend';

    /**
     * Перевірка на дозвіл
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
     * Пошук моделі по первинному ключу
     * Якщо модель не знайдена, повертається null
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
     * Пошук мовної моделі по первинному ключу
     * Якщо модель не знайдена, повертається null
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
     * Формує посилання куди повертаємося після зберігання
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
     * Повертає модель даных, що створена в процесі роботи
     * @return object ActiveRecord
     */
    public function getModel() {
        return $this->model;
    }

}
