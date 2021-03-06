<?php

namespace filamentv\app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Class ActiveRecord
 * Exted basic ActiveRecord some basic methods and attributes
 * 
 * @package filamentv\app\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */
abstract class ActiveRecord extends \yii\db\ActiveRecord {

    const STATUS_KEY_ON = '1';
    const STATUS_KEY_OFF = '0';

    /**
     * @var ActiveQuery
     */
    public static $commonQuery = query\ActiveQuery::class;

    /**
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
     * @return type
     */
    public static function find() {
        return new static::$commonQuery(get_called_class());
    }

    /**
     * @return []
     */
    public static function statusKeyRange() {
        return [
            static::STATUS_KEY_ON => Yii::t('app', 'KEY_ON'),
            static::STATUS_KEY_OFF => Yii::t('app', 'KEY_OFF')
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
