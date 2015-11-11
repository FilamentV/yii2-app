<?php

namespace filamentv\app\helpers\tree\one;

/**
 * Class TreeModelTrait
 * Trait for ActiveRecord [[TreeModelTrait]]
 * 
 * @package filamentv\app\helpers\tree\one
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */
trait TreeModelTrait {

    use \filamentv\app\helpers\tree\TreeModelBaseTrait;

    /**
     * 
     * @param integer $tree
     * @return boolean
     */
    public static function setTreeCurrent($tree) {
        return true;
    }

    /**
     * 
     * @param integer $tree
     */
    public static function destructTree($tree = 0) {
        static::$treeCache[0] = [];
    }

    /**
     * 
     * @param integer $tree
     */
    public static function fillinTree($tree = 0) {
        foreach (static::$treeModelCache as $model) {
            static::$treeCache[0][$model->level][$model->parent][] = $model->id;
        }
    }

    /**
     * 
     */
    public function validateParentPath() {

        if ($this->parent > 0) {
            static::findTreeModel(0);
            static::fillinTree(0);
            $path = static::getPathById($this->parent);
            $state = false;
            foreach ($path as $m)
                if ($m->id == $this->id)
                    $state = true;

            if ($state === true)
                $this->addError('parent', 'No move in this parent');
        }
    }

}
