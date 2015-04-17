<?php

namespace filamentv\app\actions\fileapi;

use yii\base\Action;
use yii\helpers\FileHelper;
use yii\web\Response;
use Yii;

/**
 * Class UploadAction
 *
 * @package filamentv\app\actions\fileapi
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 15/04/2015
 */
class DeleteAction extends Action {

    /**
     * @var string Path to directory where files has been uploaded
     */
    public $path;

    /**
     *
     * @var string
     */
    public $paramName = 'file';

    /**
     * @inheritdoc
     */
    public function init() {
        //default path
        if ($this->path === null)
            $this->path = Yii::getAlias('@temp');

        $this->path = FileHelper::normalizePath($this->path) . DIRECTORY_SEPARATOR;
    }

    /**
     * @inheritdoc
     */
    public function run() {
        $error = 'file don\'t exist';

        if (($file = Yii::$app->getRequest()->post($this->paramName))) {

            $filename = FileHelper::normalizePath($this->path . '/' . $file);

            if (is_file($filename)) {
                $error = (unlink($filename)) ? 'file deleted' : 'can not delete file';
            }
        }

        if (\Yii::$app->getRequest()->isAjax)
            Yii::$app->response->format = Response::FORMAT_JSON;

        $result = ['error' => $error];

        return $result;
    }

}
