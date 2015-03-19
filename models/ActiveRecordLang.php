<?php

namespace filament\app\models;

use Yii;

/**
 * @package filament\app\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 20/03/2015
 */
abstract class ActiveRecordLang extends \yii\db\ActiveRecord {

    public function init() {
        parent::init();
    }

    /**
     * Визначення базових поведінок
     * @return []
     */
    public function behaviors() {

        return [
        ];
    }

    /**
     * Перевизначення базового find() для додавання default scopes
     * @return type
     */
    public static function find() {
        return parent::find()->andWhere([static::tableName() . '.lang' => Yii::$app->language]);
    }

    /**
     * Визначення базових сценаріїв
     * @return []
     */
    public function scenarios() {
        return [
            'backend' => ['title'],
        ];
    }

    /**
     * Повертає наявність атрибута в моделі
     * @param type $attribute
     * @return boollean
     */
    public function is_attribute($attribute) {
        return (in_array($attribute, $this->attributes())) ? true : false;
    }

    /**
     * Повертає наявність сценарія в моделі
     * @param type $scenario
     * @return boollean
     */
    public function is_scenario($scenario) {
        return (array_key_exists($scenario, $this->scenarios())) ? true : false;
    }

}
