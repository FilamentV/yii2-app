# Використання
# ActiveRecord

use filamentv\app\helpers\tree\multi\MultiTreeBehavior;

class ActiveRecord {

    use \filamentv\app\helpers\tree\multi\MultiTreeModelTrait;

    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
...
                    'multitree' => [
                        'class' => MultiTreeBehavior::class,
                    ],
...
        ]);
    }

}

#ActiveQuery
class ActiveQuery {
    use \filamentv\app\helpers\tree\TreeActiveQuery;
}