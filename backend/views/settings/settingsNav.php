<?php
use yii\bootstrap\Nav;

echo Nav::widget([
                'items' => [
                    [
                      'label' => 'Пользователи системы',
                      'url' => ['/settings/index'],
                    ],
                    [
                      'label' => 'Сути обращений',
                      'url' => ['/settings/reasons'],
                    ],
                    [
                      'label' => 'Общие справочники',
                      'url' => ['/settings/commons'],
                    ],
                    [
                      'label' => 'Организации',
                      'url' => ['/settings/company'],
                    ],
                    [
                      'label' => 'Глобальные параметры',
                      'url' => ['/settings/global-list'],
                    ],
                ],

                'options' => ['class' =>'nav-tabs']
     ]);
