<?php

namespace thread\models;

use Yii;
use thread\behaviors\PurifierBehavior;

/**
 * Class ActiveRecordLang
 * Common-модель ActiveRecord для мовної частини [[ActiveRecordLang]]
 * @package thread\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2014, Thread
 * @version 19/03/2015
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
            'purifierBehavior' => [
                'class' => PurifierBehavior::className(),
                'textAttributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['title'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['title'],
                ],
                'purifierOptions' => [
                    'HTML.AllowedElements' => Yii::$app->params['allowHtmlTags']
                ]
            ],
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
