<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="modal fade" id="uploadDialogModal" tabindex="-1" role="dialog" aria-labelledby="uploadDialogModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Выберите файл</h4>
        </div>
        <div class="modal-body">
          <?= $form->field($uploadModel, 'file')->fileInput()->label(false) ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Загрузить</button>
        </div>

        <?php ActiveForm::end() ?>
      </div>
    </div>
  </div>
