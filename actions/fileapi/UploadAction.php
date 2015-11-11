<?php

namespace filamentv\app\actions\fileapi;

use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class UploadAction
 *
 * @package filamentv\app\actions\fileapi
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 */
class UploadAction extends Action {

    /**
     * @var string Path to directory where files will be uploaded
     */
    public $path;

    /**
     * The name form data send in get request ($_GET['input_file_name'])
     * @var boolean
     */
    public $getParamName = 'input_file_name';

    /**
     * @var string The parameter name for the file form data (the request argument name).
     */
    public $paramName = 'file';

    /**
     * @var boolean If `true` unique filename will be generated automatically
     */
    public $unique = true;

    /**
     * @var boollean
     */
    public $uploadOnlyImage = true;

    /**
     * @var array Model validator options
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

    /**
     * 
     * @throws InvalidCallException
     */
    public function init() {
        //default path
        if ($this->path === null) {
            $this->path = Yii::getAlias('@temp');
        }

        $this->path = FileHelper::normalizePath(Yii::getAlias($this->path)) . DIRECTORY_SEPARATOR;

        if (!FileHelper::createDirectory($this->path)) {
            throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
        }
        //set validator model type
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }
    }

    /**
     * 
     * @return type
     * @throws BadRequestHttpException
     */
    public function run() {
        if (Yii::$app->request->isPost) {

            if (!empty($this->getParamName) && Yii::$app->getRequest()->get($this->getParamName)) {
                $this->paramName = Yii::$app->getRequest()->get($this->getParamName);
            }

            $file = UploadedFile::getInstanceByName($this->paramName);

            $model = new DynamicModel(compact('file'));
            $model->addRule('file', $this->_validator, $this->validatorOptions);

            if ($model->validate()) {

                if ($this->unique === true) {
                    $model->file->name = uniqid() . ((empty($model->file->extension)) ? '' : '.' . $model->file->extension);
                }

                $result = ($model->file->saveAs($this->path . $model->file->name)) ?
                        ['key' => $model->file->name, 'caption' => $model->file->name, 'name' => $model->file->name] :
                        ['error' => 'Can\'t upload file'];
            } else {
                $result = ['error' => $model->getErrors()];
            }

            if (Yii::$app->getRequest()->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
            }

            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }

}
