<?php

namespace filamentv\app\models\query;

use filamentv\app\models\ActiveRecord;

/**
 * Class CommonQuery
 * Common-запитів базовий ActiveQuery
 * @package filamentv\app\models\query
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 19/03/2015
 */
class ActiveQuery extends \yii\db\ActiveQuery {

    /**
     * Записи за полем alias
     * @return ActiveQuery $this
     */
    public function alias($alias) {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.alias = :alias', [':alias' => $alias]);
        return $this;
    }

    /**
     * Записи з поміткою на публікацію та не помічені на видалення
     * @return ActiveQuery $this
     */
    public function enabled() {
        return $this->published()->undeleted();
    }

    /**
     * Записи з поміткою на публікацію
     * @return ActiveQuery $this
     */
    public function published() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.published = :published', [':published' => ActiveRecord::STATUS_KEY_ON]);
        return $this;
    }

    /**
     * Записи без помітки на публікацію
     * @return ActiveQuery $this
     */
    public function unpublished() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.published = :published', [':published' => ActiveRecord::STATUS_KEY_OFF]);
        return $this;
    }

    /**
     * Записи з поміткою на видалення
     * @return ActiveQuery $this
     */
    public function deleted() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.deleted = :deleted', [':deleted' => ActiveRecord::STATUS_KEY_ON]);
        return $this;
    }

    /**
     * Записи без помітки на видалення
     * @return ActiveQuery $this
     */
    public function undeleted() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.deleted = :deleted', [':deleted' => ActiveRecord::STATUS_KEY_OFF]);
        return $this;
    }

    /**
     * Записи з поміткою тільки читання
     * @return ActiveQuery $this
     */
    public function readonly() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.readonly = :readonly', [':readonly' => ActiveRecord::STATUS_KEY_OFF]);
        return $this;
    }

    /**
     * Встановлює поле lang
     * @return ActiveQuery $this
     */
    public function _lang() {
        return $this->andWhere(['lang' => \Yii::$app->language]);
    }

    /**
     * Запис з встановленим полем group_id
     * @param string $group_id
     * @return ActiveQuery $this
     */
    public function group_id($group_id) {
        $modelClass = $this->modelClass;

        $this->andWhere($modelClass::tableName() . '.group_id = :group_id', [':group_id' => $group_id]);

        return $this;
    }

    /**
     * Запис з встановленим полем id
     * @param type $id
     * @return ActiveQuery $this
     */
    public function byID($id) {
        $modelClass = $this->modelClass;

        $this->andWhere($modelClass::tableName() . '.id = :id', [':id' => $id]);

        return $this;
    }

    /**
     * Звязує запис з даними мовної таблиці
     * @return ActiveQuery $this
     */
    public function lang() {
        $modelClass = $this->modelClass . "Lang";

        $this->leftJoin($modelClass::tableName(), 'rid = id')->_lang();

        return $this;
    }

}
