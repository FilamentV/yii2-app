uses [components/I18N]:

    'components' => [
        'i18n' => [
            'class' => 'filamentv\app\components\I18N',
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ]
        ],
    ],

uses [components/Pagination]:

'class' => 'filamentv\app\components\Pagination'


Instalation
===

    composer.phar require "filamentv/yii2-app":"dev-master"

or add to composer.json into 'require' seaction

    "filamentv/yii2-app":"dev-master"
