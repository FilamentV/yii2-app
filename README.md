uses [components/I18N]:

    'components' => [
...
        'i18n' => [
            'class' => 'filamentv\app\components\I18N',
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@thread/messages',
                ],
            ]
        ],
...
    ],

uses [components/Pagination]:

'class' => 'filamentv\app\components\Pagination'
