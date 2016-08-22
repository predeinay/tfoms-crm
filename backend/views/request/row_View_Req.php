<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<div class="request">
    <div class="panel panel-default">
        <div class="panel-body <?= $model->status_text == 'В работе'?'grid-yellow':'' ?>">
          <div class="row">
             <div class="col-lg-7 col-md-7 col-sm-7">
                <?=
                Html::a('<span class="req-edit-link">
                          Обращение #'.$model->req_id.' от '.
                          Yii::$app->myhelper->to_beauty_date_time($model->created_on)
                          .' | '.$model->user_name.
                        '</span>',
                        ['request/form', 'id' => $model->req_id ]) ?>

                </a>
             </div>
             <div class="col-lg-3 col-md-3 col-sm-3">
                 <em style='font-size: 0.9em;'>
                 <?= $model->company_name ?>
                 </em>
             </div>
             <div class="col-lg-2 col-md-2 col-sm-2">
                 <?= $model->status_text == 'закрыто'?
                                             '<b><span class="label label-default">'.$model->status_text.'</span></b>':
                                             '<b><span class="label label-warning">'.$model->status_text.'</span></b>' ?>
             </div>

          </div>
            <br>
          <div class="row">
             <div class="col-lg-12">
                <?= strlen($model->surname.$model->name.$model->patronymic) > 0 ? 'Обратился':'' ?>
                <?= $model->surname ?>
                <?= $model->name ?>
                <?= $model->patronymic ?>
                <?= $model->address ?>
                <?= strlen($model->surname.$model->name.$model->patronymic.$model->address) > 0 ? '<br/>':'' ?>
                <?= $model->note ?>

                <?= strlen($model->result_text)>0 ? '<br/><span class="label label-info">'.$model->result_text.'</span>':'' ?>
             </div>
          </div>
        </div>
        <div class="panel-footer req-list-footer">
            <?= $model->form_text ?> |
            <?= $model->way_text ?> |
            <?= $model->kind_text ?> |
            <?= $model->reason_text ?>
        </div>
    </div>
</div>
