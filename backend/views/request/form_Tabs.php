<?php
use yii\bootstrap\Nav;

function getBadge($count) {
  return $count==0?'':'<span class="badge" style="background-color: #337ab7;">'.$count.'</span>';
}

echo Nav::widget([
    'items' => [
        [
          'label' => '<!--span class="glyphicon glyphicon-folder-open"></span--> Общая информация',
          'url' => ['/request/form', 'id' => $requestModel->req_id ],
        ],
        [
          'label' => 'Комментарии '.getBadge($requestModel->getCommentCount()),
          'url' => ['/request/comments', 'id' => $requestModel->req_id],
        ],
        [
          'label' => 'Файлы '.getBadge($requestModel->getFileCount()),
          'url' => ['/request/files', 'id' => $requestModel->req_id ],
        ],
    ],

    'options' => ['class' =>'nav nav-tabs'],
    'encodeLabels' => false,
 ]);
