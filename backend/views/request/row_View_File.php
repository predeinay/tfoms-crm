<?php
use yii\helpers\Html;

?>
<div class="comment container-fluid" style="margin-top: 15px;">
  <div class="row">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default" style="margin-bottom:0px;">
          <div class="panel-body">
            <?=
            Html::a($model->file_name,$model->file_path) ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <span style="font-size: 0.8em;float: right; color: #777;">
          Загружено
          <?= Yii::$app->myhelper->to_beauty_date_time($model->created_on) ?>
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <span style="float: right;font-size: 0.9em;">
          <?= $model->user_name ?>
        </span>
      </div>
    </div>
  </div>
</div>
