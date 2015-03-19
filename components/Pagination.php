<?php

namespace thread\components;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class Pagination
 * Common Pagination [[Pagination]]
 * @package thread\components
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2014, Thread
 * @version 19/03/2015
 */
class Pagination extends \yii\data\Pagination {

    public function setPage($value, $validatePage = false) {
        parent::setPage($value, $validatePage = false);

        if ($value < 0)
            throw new NotFoundHttpException;

        $pageCount = $this->getPageCount();
        if ($value >= $pageCount)
            throw new NotFoundHttpException;

        if (Yii::$app->getRequest()->get($this->pageParam) == 1)
            throw new NotFoundHttpException;
    }

    public function createUrl($page, $pageSize = null, $absolute = false) {
        if (($params = $this->params) === null) {
            $request = Yii::$app->getRequest();
            $params = $request instanceof Request ? $request->getQueryParams() : [];
        }
        if ($page > 1 || $page >= 1 && $this->forcePageParam) {
            $params[$this->pageParam] = $page + 1;
        } else {
            unset($params[$this->pageParam]);
        }

        $params[0] = $this->route === null ? Yii::$app->controller->getRoute() : $this->route;
        $urlManager = $this->urlManager === null ? Yii::$app->getUrlManager() : $this->urlManager;
        if ($absolute) {
            return $urlManager->createAbsoluteUrl($params);
        } else {
            return $urlManager->createUrl($params);
        }
    }

}
