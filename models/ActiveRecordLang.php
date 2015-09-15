<?php

namespace filamentv\app\models;

use Yii;

/**
 * Class ActiveRecordLang
 * @package filamentv\app\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 25/03/2015
 */
abstract class ActiveRecordLang extends \yii\db\ActiveRecord {

    /**
     * @return []
     */
    public function behaviors() {

        return [
        ];
    }

    /**
     * @return type
     */
    public static function find() {
        return parent::find()->onCondition([static::tableName() . '.lang' => Yii::$app->language]);
    }

    /**
     * @return []
     */
    public function scenarios() {
        return [
        ];
    }

    /**
     * @param type $attribute
     * @return boollean
     */
    public function is_attribute($attribute) {
        return (in_array($attribute, $this->attributes())) ? true : false;
    }

    /**
     * @param type $scenario
     * @return boollean
     */
    public function is_scenario($scenario) {
        return (array_key_exists($scenario, $this->scenarios())) ? true : false;
    }

}
