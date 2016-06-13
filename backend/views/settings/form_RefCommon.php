<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$action == 'create' ? $this->title = 'Создание нового значения' : $this->title = 'Редактирование значения' ;

$this->params['breadcrumbs'][] = ['label' => 'Общие справочники', 'url' => ['/settings/commons']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h3><?= $this->title.' '.$model->text ?></h3>
    </div>    
    <div class="col-lg-6">
    <?php

    $form = ActiveForm::begin([
        //'id' => 'queue-form',
        'action' => $action == 'create' ? ['settings/common-create'] : ['settings/common-update','id' => $model->ref_id ]
    ]);
    
    ?>
    <?= $form->field($model, 'ref_id')->hiddenInput() ?>    
        
    <?= $form->field($model, 'type')->dropDownList($typeArr )->hint('Название справочника') ?>
        
    <?= $form->field($model, 'text')->textInput()->hint('Значение') ?>

    <?= Html::a('Вернуться',['settings/commons'],['class'=>'btn']) ?>
      
    <?php
        if (!is_null($model->ref_id)) {
            echo Html::a('Удалить',['settings/common-delete', 'id' => $model->ref_id],['class'=>'btn btn-default']);
        }
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать значение', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
        
    <?php ActiveForm::end() ?>
    </div>
</div>