<?php

use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Комментарии к обращению';

$this->params['breadcrumbs'][] = ['label' => 'Список обращений', 'url' => ['/request/list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <?= $this->render('form_Tabs',['requestModel' => $requestModel ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6" style="margin-top: 15px;">
            <?php
              echo ListView::widget([
                    'dataProvider' => $commentsProvider,
                    'itemView' => 'row_View_Comment',
                    'summary'=>''
                    ]);
            ?>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-6" style="margin-top: 15px;">
        <?php $form = ActiveForm::begin([
              'action' => ['request/create-comment','requestId' => $requestModel->req_id ],
              'id' => 'add-comment-form'
           ]); ?>
        <?= Html::activeHiddenInput($newCommentModel, 'request_id')?>
        <?= $form->field($newCommentModel, 'comment')->textarea(
                                    ['placeholder' => 'Текст вашего нового комментария',
                                     'rows' => 5 ])->label('')
        ?>
        <?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-primary']); ?>

        <?php ActiveForm::end(); ?>

      </div>
    </div>
</div>
