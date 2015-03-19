<?php

namespace thread\app\components;

/**
 * @package thread\app\components
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2014, Thread
 * @version 19/03/2015
 */
class I18N extends \yii\i18n\I18N {

    public function translate($category, $message, $params, $language) {
        return parent::translate($category, $message, $params, \Yii::$app->params['themesLanguage']);
    }

}
