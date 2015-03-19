<?php

namespace filament\app\components;

/**
 * Separate Application Language and Themes Language
 * @package filament\app\components
 * @author FilamentV <vortex.filament@gmail.com>
 * @copyright (c) 2015, Thread
 * @version 20/03/2015
 */
class I18N extends \yii\i18n\I18N {

    public function translate($category, $message, $params, $language) {
        return parent::translate($category, $message, $params, \Yii::$app->params['themesLanguage']);
    }

}
