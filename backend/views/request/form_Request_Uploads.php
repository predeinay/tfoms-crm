<?php

use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Загруженные файлы к обращению';

$this->params['breadcrumbs'][] = ['label' => 'Список обращений', 'url' => ['/request/list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <?= $this->render('form_Tabs',['req_id' => $req_id ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6" style="margin-top: 15px;">
            <?php
              echo ListView::widget([
                    'dataProvider' => $uploadsProvider,
                    'itemView' => 'row_View_File',
                    'summary'=>''
                    ]);
            ?>
        </div>
    </div>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadDialogModal">
      Загрузить новый файл
    </button>

    <!-- Upload modal dialog-->
    <?= $this->render('/dialogs/form_Upload',['uploadModel' => $uploadModel ]) ?>


</div>
