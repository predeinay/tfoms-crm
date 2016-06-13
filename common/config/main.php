<?php
return [
    'language' => 'ru-RU',
    'timeZone' => 'UTC',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
                'nullDisplay' => '&nbsp;',
        ],
    ],
];
