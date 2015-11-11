<?php

namespace filamentv\app\models\query;

use filamentv\app\models\ActiveRecord;

/**
 * Class ActiveQuery
 * 
 * @package filamentv\app\models\query
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 19/03/2015
 */
class ActiveQuery extends \yii\db\ActiveQuery {

    /**
     * @return ActiveQuery $this
     */
    public function alias($alias) {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.alias = :alias', [':alias' => $alias]);
        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function enabled() {
        return $this->published()->undeleted();
    }

    /**
     * @return ActiveQuery $this
     */
    public function published() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.published = :published', [':published' => ActiveRecord::STATUS_KEY_ON]);
        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function unpublished() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.published = :published', [':published' => ActiveRecord::STATUS_KEY_OFF]);
        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function deleted() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.deleted = :deleted', [':deleted' => ActiveRecord::STATUS_KEY_ON]);
        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function undeleted() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.deleted = :deleted', [':deleted' => ActiveRecord::STATUS_KEY_OFF]);
        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function readonly() {
        $modelClass = $this->modelClass;
        $this->andWhere($modelClass::tableName() . '.readonly = :readonly', [':readonly' => ActiveRecord::STATUS_KEY_OFF]);
        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function _lang() {
        return $this->andWhere(['lang' => \Yii::$app->language]);
    }

    /**
     * @param string $group_id
     * @return ActiveQuery $this
     */
    public function group_id($group_id) {
        $modelClass = $this->modelClass;

        $this->andWhere($modelClass::tableName() . '.group_id = :group_id', [':group_id' => $group_id]);

        return $this;
    }

    /**
     * @param type $id
     * @return ActiveQuery $this
     */
    public function byID($id) {
        $modelClass = $this->modelClass;

        $this->andWhere($modelClass::tableName() . '.id = :id', [':id' => $id]);

        return $this;
    }

    /**
     * @param array $IDs
     * @return ActiveQuery $this
     */
    public function rangeID(array $IDs) {
        $modelClass = $this->modelClass;

        $this->andWhere(['in', $modelClass::tableName() . '.id', $IDs]);

        return $this;
    }

    /**
     * @param array $IDs
     * @return ActiveQuery $this
     */
    public function withoutIDs(array $IDs) {
        $modelClass = $this->modelClass;

        $this->andWhere(['not in', $modelClass::tableName() . '.id', $IDs]);

        return $this;
    }

    /**
     * @return ActiveQuery $this
     */
    public function lang() {
        $modelClass = $this->modelClass . "Lang";

        $this->leftJoin($modelClass::tableName(), 'rid = id')->_lang();

        return $this;
    }

}
