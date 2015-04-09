<?php

namespace filamentv\app\helpers\tree;

use yii\helpers\ArrayHelper;

/**
 * Class TreeModelBaseTrait
 * 
 * @package thread\helpers\tree
 * @author Filament
 * @copyright (c) 2015, Thread
 * @version 23/02/2015
 */
trait TreeModelBaseTrait {

    /**
     * Роздільник, що використовується для створення full_alias
     * @var string
     */
    public $delimiterFullAlias = '-';

    /**
     * Зберігає відбудоване дерево
     *
     * [tree][level][parent] = [id]
     * @var array
     */
    protected static $treeCache = [];

    /**
     * Зберігає моделі
     * [id] = ActiveModel
     * @var array
     */
    protected static $treeModelCache = [];

    /**
     * Встановлюэмо поточне дерево
     * @var integere
     */
    protected static $treeCurrent = 0;

    public static function initTree($tree = 0) {
        self::findTreeModel($tree);
        self::fillinTree($tree);
    }

    /**
     * Встановлює поточне дерево ат повертає статус виконання операції
     * @param integer $tree
     * @return boolean
     */
    public static function setTreeCurrent($tree) {
        $tree = (int) $tree;
        if (isset(static::$treeCache[$tree])) {
            static::$treeCurrent = $tree;
            return true;
        }
        return false;
    }

    /**
     * Повертає шлях від початку дерева по місця пошуку
     * @param integer $id
     * @return array|null
     */
    public static function getPathById($id) {
        $id = (int) $id;
        if (isset(static::$treeModelCache[$id])) {
            $return = array();
            $parent = $id;
            do {
                $m = static::$treeModelCache[$parent];
                $parent = $m->parent;
                $return[] = $m;
            } while ($m->level > 0);
            return array_reverse($return);
        }
        return null;
    }

    /**
     * Повертає повну гілку дерева
     * @param type $id
     * @return array|null
     */
    public static function getBranchById($id) {
        $id = (int) $id;
        if (isset(static::$treeModelCache[$id])) {
            return static::createTreeById($id);
        }
        return null;
    }

    /**
     * Створює дерево відносно висначеного кореня
     * @param integer $id
     * @return array|null
     */
    protected static function createTreeById($id) {
        $id = (int) $id;
        if (isset(static::$treeModelCache[$id])) {
            $return = array();
            if ($menu = static::getSubLevelByParentId($id)) {
                foreach ($menu as $model) {
                    $return[] = $model;
                    if ($r = static::createTreeById($model->id)) {
                        $return = ArrayHelper::merge($return, $r);
                    }
                }
                return $return;
            }
        }
        return null;
    }

    /**
     * Створення впорядкованого дерева
     * @return array
     */
    public static function createTree() {
        $return = array();
        foreach (static::$treeCache[static::$treeCurrent][0][0] as $k) {
            $return[] = static::$treeModelCache[$k];
            if ($menu = static::getSubLevelByParentId($k)) {
                foreach ($menu as $model) {
                    $return[] = $model;
                    if ($r = static::createTreeById($model->id)) {
                        $return = ArrayHelper::merge($return, $r);
                    }
                }
            }
        }
        return $return;
    }

    /**
     * Повертає підменю по визначеному id батька
     * @param integere $parent
     * @return array|null
     */
    public static function getSubLevelByParentId($parent) {
        if (isset(static::$treeModelCache[$parent])) {
            $model = static::$treeModelCache[$parent];
            if (isset(static::$treeCache[static::$treeCurrent][$model->level + 1]) &&
                    isset(static::$treeCache[static::$treeCurrent][$model->level + 1][$model->id]) &&
                    $submenu = static::$treeCache[static::$treeCurrent][$model->level + 1][$model->id]
            ) {
                $return = array();
                foreach ($submenu as $id) {
                    $return[$id] = static::$treeModelCache[$id];
                }
                return $return;
            }
            return null;
        }
        return null;
    }

    /**
     *
     * @return type
     */
    public static function getBaseLevel() {
        $models = static::$treeCache[static::$treeCurrent][0][0];
        if (!empty($models)) {
            $return = array();
            foreach ($models as $id) {
                $return[$id] = static::$treeModelCache[$id];
            }
            return $return;
        }
        return null;
    }

    /**
     * Повертає структуру поточного дерева
     * @return array
     */
    public static function getTreeStructure() {
        return static::$treeCache[static::$treeCurrent];
    }

    /**
     * Очищення створеної структури
     * @param integer $tree
     */
    public static function destructTree($tree = 0) {
        $tree = (int) $tree;
        if (isset(static::$treeModelCache[$tree])) {
            static::$treeCache[$tree] = [];
        }
    }

    /**
     * @param integer $tree
     */
    public static function findTreeModel($tree = 0) {
        $models = static::find()->tree($tree)->addOrderBy(['position' => SORT_ASC])->all();
        foreach ($models as $model) {
            //заповнюємо моделі
            static::$treeModelCache[$model->id] = $model;
        }
    }

    /**
     * Заповнює дерево
     * @param integer $tree
     */
    public static function fillinTree($tree = 0) {
        foreach (static::$treeModelCache as $model)
            if ($model->tree === $tree)
                static::$treeCache[$tree][$model->level][$model->parent][] = $model->id;
    }

    /**
     * Повертає модель батька поточної моделі
     * @return type
     */
    public function getParent() {
        return isset(static::$treeModelCache[$this->parent]) ? static::$treeModelCache[$this->parent] : null;
    }

    /**
     * Повернення моделі з кеша даних
     * @param integer $id
     * @return ActiveRecord|null
     */
    public static function getModelFromCacheById($id) {
        return isset(static::$treeModelCache[$id]) ? static::$treeModelCache[$id] : null;
    }

    /**
     * Перевірка чи модель не переноситься в себе
     * використовуєтсья в validate
     */
    public function validateParentPath() {

        if ($this->parent > 0) {
            static::findTreeModel(static::$treeCurrent);
            static::fillinTree(static::$treeCurrent);
            $path = static::getPathById($this->parent);
            $state = false;
            foreach ($path as $m)
                if ($m->id == $this->id)
                    $state = true;

            if ($state === true)
                $this->addError('parent', 'No move in this parent');
        }
    }

    /**
     * Перевіряє та встановлює правильне значення атрибуту level
     * ActiveRecord::EVENT_BEFORE_INSERT => [$this->owner, 'validateLevel']
     * ActiveRecord::EVENT_BEFORE_UPDATE => [$this->owner, 'validateLevel']
     */
    public function validateLevel() {
        if ($this->parent > 0) {
            static::findTreeModel(static::$treeCurrent);
            static::fillinTree(static::$treeCurrent);
            $parent = static::getParent($this->parent);
            $this->level = $parent->level + 1;
        } else {
            $this->level = 0;
        }
    }

    /**
     * Створює та встановлює значення атрибуту full_alias
     * ActiveRecord::EVENT_BEFORE_VALIDATE => [$this->owner, 'createFullAlias']
     */
    public function createFullAlias() {
//        if (strlen($this->full_alias) == 0) {
        static::findTreeModel(static::$treeCurrent);
        static::fillinTree(static::$treeCurrent);

        if ($this->parent > 0) {
            $path = static::getPathById($this->parent);
            $alias = array();
            foreach ($path as $m) {
                $alias[] = $m->alias;
            }
            $alias[] = $this->alias;

            $this->full_alias = implode($this->delimiterFullAlias, $alias);
        } else {
            $this->full_alias = $this->alias;
        }
//        }
    }

    /**
     * Реструктуризація частини дерева, що підпорядкована
     * даній моделі
     * ActiveRecord::EVENT_AFTER_UPDATE => [$this->owner, 'restructureSubTree']
     */
    public function restructureSubTree() {
        $tree = static::createTreeById($this->id);
        if ($tree !== null)
            foreach ($tree as $model) {
                $model->level = $this->level + 1;
                $model->scenario = $this->scenario;
                $model->save();
            }
    }

}
