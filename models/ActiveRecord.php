<?php

namespace thread\models;

use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveRecord
 * Common-модель ActiveRecord [[ActiveRecord]]
 * @package thread\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2014, Thread
 * @version 19/03/2015
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord {

    const STATUS_KEY_ON = '1';
    const STATUS_KEY_OFF = '0';

    /**
     * Встановлення класу ActiveQuery
     * @var ActiveQuery
     */
    public static $commonQuery = query\ActiveQuery::class;

    /**
     * Визначення базових поведінок
     * @return []
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['update_time', 'create_time'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
        ];
    }

    /**
     * Перевизначення базового find()
     * @return type
     */
    public static function find() {
        return new static::$commonQuery(get_called_class());
    }

    /**
     * Визначені статуси для полів типа ключ
     * @return []
     */
    public static function statusKeyRage() {
        return [
            static::STATUS_KEY_ON,
            static::STATUS_KEY_OFF
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

    /**
     * Оновлює звязок
     * @param array $keys
     * @param ActiveRecord $class
     * @param string $link
     */
    protected function relationRefresh($keys, $class, $link) {
        $item = $class::findAll($keys);

        foreach ($this->$link as $c)
            $this->unlink($link, $c, true);

        foreach ($item as $i)
            $this->link($link, $i);
    }

    /**
     * 
     * @return array [id=>title]
     */
    public static function dropDownList() {
        return ArrayHelper::merge(['0' => ''], ArrayHelper::map(static::all(), 'id', 'lang.title'));
    }

}
