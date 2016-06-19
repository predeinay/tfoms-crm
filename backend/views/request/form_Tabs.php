<?php
use yii\bootstrap\Nav;

            echo Nav::widget([
                'items' => [
                    [ 
                      'label' => '<!--span class="glyphicon glyphicon-folder-open"></span--> Общая информация',
                      'url' => ['/request/form', 'id' => $req_id ],
                    ],
                    [ 
                      'label' => '<!--span class="glyphicon glyphicon-comment"></span--> Комментарии',
                      'url' => ['/request/comments', 'id' => $req_id],
                    ],
                    [ 
                      'label' => '<!--span class="glyphicon glyphicon-headphones"></span--> Записи разговоров',
                      'url' => ['/request/records', 'id' => $req_id],
                    ],
                ],
    
                'options' => ['class' =>'nav nav-tabs'],
                'encodeLabels' => false,
             ]);