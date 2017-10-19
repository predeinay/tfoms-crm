<?php

?>
<div class="comment container-fluid" style="margin-top: 15px;">
  <div class="row">
    <div class="row">
      <div class="col-lg-12">
        <div class="alert alert-success" role="alert" style="margin-bottom:0px;">
          <?= $model->comment ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <span style="font-size: 0.8em;float: right; color: #777;">
          Написано
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
