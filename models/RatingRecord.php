<?php

namespace filamentv\app\models;

use app\modules\user\User;

/**
 * Class RatingRecord
 *
 * @package thread\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */
abstract class RatingRecord extends ActiveRecord {

    /**
     * It's a parent object
     * @var string
     */
    protected static $obj = null;

    /**
     * It's a parent object scenario name
     * scenario(){
     * 'ratingupdate' => ['ratingCount', 'ratingValue'],
     * }
     * @var string
     */
    protected static $obj_scenario = 'ratingupdate';

    /**
     * the name of attribute parent object
     * count - type's integer
     * @var string
     */
    protected static $obj_att_rating_count = 'ratingCount';

    /**
     * the name of attribute parent object
     * value - type's float
     * @var string
     */
    protected static $obj_att_rating_value = 'ratingValue';

    /**
     * @inheritdoc
     */
    public static $commonQuery = query\RatingQuery::class;

    /**
     * @inheritdoc
     */
    abstract public static function tableName();

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['item_id', 'user_id', 'create_time', 'update_time'], 'integer'],
            ['item_id', 'exist', 'targetClass' => self::$obj, 'targetAttribute' => 'id'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
            [['rate'], 'in', 'range' => array_keys(self::rateRange())],
            [['item_id', 'user_id'], 'unique', 'targetAttribute' => ['item_id', 'user_id'], 'message' => 'The combination of rid and lang has already been taken.'],
            [['rate'], 'default', 'value' => '1'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return [
            'backend' => ['article_id', 'user_id', 'rate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'id'),
            'article_id' => Yii::t('app', 'article'),
            'user_id' => Yii::t('app', 'user'),
            'rate' => Yii::t('catalog', 'rate'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function base_find() {
        return static::find();
    }

    /**
     *
     * @return array
     */
    public static function rateRange() {
        return [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ];
    }

    /**
     *
     * @param integer $item_id
     * @return \yii\db\ActiveQuery
     */
    public static function findByItem($item_id) {
        return static::base_find()->item_id($item_id);
    }

    /**
     *
     * @param integer $user_id
     * @return \yii\db\ActiveQuery
     */
    public static function findByUser($user_id) {
        return static::base_find()->user_id($user_id);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        $this->refreshCountObject();
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * refresh attributes in parent object
     */
    public function refreshCountObject() {
        $count = self::findByItem($this->item_id)->count();
        $sum = self::findByItem($this->item_id)->sum('rate');

        $m = new self::$obj;
        $m->scenario = self::$obj_scenario;
        $m->ratingCount = $count;
        $m->ratingValue = $sum / $count;
        $m->save();
    }

}
