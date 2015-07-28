<?php

namespace filamentv\app\actions;

use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use filamentv\app\components\Pagination;

/**
 * Class ListQuery
 * 
 * @package filamentv\app\actions
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 22/03/2015
 *
  public function actions() {
  return [
 * ...
  'list' => [
  'class' => QueryList::class,
  'query' => $model::find(),
  'recordOnPage' => 10
  ],
 * ...
  ];
  }
 *
 */
class ListQuery extends Action {

    /**
     * The base layout
     * @var string
     */
    public $layout = '@app/layouts/column1';

    /**
     * The view file
     * @var string
     */
    public $view = 'list';

    /**
     * @var boollean false|true
     */
    public $checkAccess = false;

    /**
     * @var ActiveQuery
     */
    public $query = null;

    /**
     * Record on page
     * @var integer
     */
    public $recordOnPage = -1;

    /**
     *
     * @var string
     */
    public $sort = '';

    /**
     * 
     * @throws Exception
     */
    public function init() {

        if ($this->query === null) {
            throw new Exception('::Query must be set.');
        }
    }

    /**
     * 
     * @return type
     */
    public function run() {

        $data = new ActiveDataProvider([
            'query' => $this->query,
            'pagination' => [
                'class' => Pagination::class,
                'pageSize' => $this->recordOnPage
            ]
        ]);
        $data->query->addOrderBy($this->sort);

        if (Yii::$app->getRequest()->isAjax) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            return $this->controller->renderPartial($this->view, [
                        'model' => $data->getModels(),
            ]);
        } else {
            $this->controller->layout = $this->layout;

            return $this->controller->render($this->view, [
                        'model' => $data->getModels(),
                        'pages' => $data->getPagination(),
            ]);
        }
    }

}
