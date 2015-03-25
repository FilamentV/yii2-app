<?php

namespace filamentv\app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 *
 * Base Language Model to use into filament\multilang
 *
 * @package filamentv\app\models
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 20/03/2015
 */
final class Lang extends ActiveRecord {

    /**
     * Model's base class
     * @var typeof Lang
     */
    protected static $current_lang = NULL;

    /**
     * Model's base class
     * @var typeof Lang
     */
    protected static $default_lang = NULL;

    /**
     * List of languages
     * @var typeof Lang
     */
    protected static $list = NULL;

    /**
     * @inheritdoc
     * @return string
     */
    public static function tableName() {
        return '{{%language}}';
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function rules() {
        return [
            [['alias', 'local', 'title'], 'required'],
            [['published', 'deleted'], 'in', 'range' => self::statusKeyRage()],
            [['update_time', 'create_time'], 'integer'],
            [['alias'], 'string', 'min' => 2, 'max' => 2],
            [['local'], 'string', 'min' => 5, 'max' => 5],
            [['title'], 'string', 'max' => 50],
            [['alias'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', 'alias'),
            'local' => Yii::t('app', 'local'),
            'title' => Yii::t('app', 'title'),
            'default' => Yii::t('app', 'default'),
            'update_time' => Yii::t('app', 'update_time'),
            'create_time' => Yii::t('app', 'create_time'),
            'published' => Yii::t('app', 'published'),
        ];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function scenarios() {
        return [
            'published' => ['published'],
            'backend' => ['alias', 'local', 'title', 'default', 'published'],
        ];
    }

    /**
     * @inheritdoc
     * @return ActiveDataProvider
     */
    public function search() {
        $query = Lang::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 99
            ]
        ]);

        return $dataProvider;
    }

    /**
     * The method return the current language model
     * @return Lang
     */
    static function getCurrent() {
        if (self::$current_lang === NULL) {
            self::setCurrentByLocal(Yii::$app->language);
        }
        return self::$current_lang;
    }

    /**
     * The method set the current language model by alias
     * @param string $alias
     */
    static function setCurrentByAlias($alias = NULL) {
        $language = self::getLangByAlias($alias);
        self::$current_lang = ($language === NULL) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current_lang->local;
    }

    /**
     * The method set the current language model by local
     * @param string $local
     */
    static function setCurrentByLocal($local = NULL) {
        $language = self::getLangByLocal($local);
        self::$current_lang = ($language === NULL) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current_lang->local;
    }

    /**
     * The method return the default language model
     * @return Lang
     */
    static function getDefaultLang() {
        if (self::$default_lang === NULL) {
            if (self::$list == NULL) {
                self::getList();
            }
            foreach (self::$list as $data) {
                if ($data->default == static::STATUS_KEY_ON) {
                    self::$default_lang = $data;
                    break;
                }
            }
        }
        return self::$default_lang;
    }

    /**
     * The method return list of languages
     * @return array Lang
     */
    static function getList() {
        if (self::$list === NULL) {

            $list_all = Lang::find()->published()->all();
            $l = array();
            foreach ($list_all as $data) {
                $l[$data->alias] = $data;
            }

            self::$list = $l;
        }
        return self::$list;
    }

    /**
     * The method return language by alias
     * @param string $alias
     * @return Lang|null
     */
    static function getLangByAlias($alias = NULL) {
        if ($alias === NULL) {
            return NULL;
        } else {
            if (self::$list == NULL) {
                self::getList();
            }
            return (isset(self::$list[$alias])) ? self::$list[$alias] : NULL;
        }
    }

    /**
     * The method return language by local
     * @param string $local
     * @return Lang|null
     */
    static function getLangByLocal($local = NULL) {
        if ($local === NULL) {
            return NULL;
        } else {
            if (self::$list == NULL) {
                self::getList();
            }
            $return = NULL;
            foreach (self::$list as $lang) {
                if ($lang->local === $local) {
                    $return = $lang;
                    break;
                }
            }
            return $return;
        }
    }

    /**
     * Status return method being language model
     * @param type $alias
     * @return boolean
     */
    static function isExists($alias = NULL) {
        if ($alias === NULL) {
            return FALSE;
        } else {
            if (self::$list == NULL)
                self::getList();
            return (isset(self::$list[$alias])) ? TRUE : FALSE;
        }
    }

}
