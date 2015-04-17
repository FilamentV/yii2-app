<?php

namespace filamentv\app\helpers\tree;

use yii\helpers\ArrayHelper;

/**
 * Class TreeModelBaseTrait
 *
 * TreeModelBaseTrait::initTree(0);
 *
 * @package thread\helpers\tree
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 23/02/2015
 */
trait TreeModelBaseTrait {

    /**
     * РSeparator used to create full_alias
     * @var string
     */
    public $delimiterFullAlias = '-';

    /**
     * Saves rebuilt tree
     *
     * [tree][level][parent] = [id]
     * @var array
     */
    protected static $treeCache = [];

    /**
     * Saves models
     * [id] = ActiveModel
     * @var array
     */
    protected static $treeModelCache = [];

    /**
     * Set the current tree
     * @var integere
     */
    protected static $treeCurrent = 0;

    /**
     *
     * @param integer $tree
     */
    public static function initTree($tree = 0) {
        self::findTreeModel($tree);
        self::fillinTree($tree);
    }

    /**
     * Sets the current tree atm returns the status of the operation
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
     * Returns the path from the beginning of the tree search destination
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
     * Returns the full tree branch
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
     * Creates a tree root relative definition
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
     * Create an ordered tree
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
     * Returns the id specified in sub father
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
     * Base level return
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
     * Returns the current tree structure
     * @return array
     */
    public static function getTreeStructure() {
        return static::$treeCache[static::$treeCurrent];
    }

    /**
     * Cleaning creating structures
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
     * Fills tree
     * @param integer $tree
     */
    public static function fillinTree($tree = 0) {
        foreach (static::$treeModelCache as $model)
            if ($model->tree === $tree)
                static::$treeCache[$tree][$model->level][$model->parent][] = $model->id;
    }

    /**
     * Returns the model father of the current model
     * @return type
     */
    public function getParent() {
        return isset(static::$treeModelCache[$this->parent]) ? static::$treeModelCache[$this->parent] : null;
    }

    /**
     * Returns the cached data model
     * @param integer $id
     * @return ActiveRecord|null
     */
    public static function getModelFromCacheById($id) {
        return isset(static::$treeModelCache[$id]) ? static::$treeModelCache[$id] : null;
    }

    /**
     * Check whether the model is not tolerated a
     * used in validate
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
     * Checks and sets the correct value attribute level
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
     * Creates and sets the attribute full_alias
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
     * Restructuring of the tree which is subject to
     * this model
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
