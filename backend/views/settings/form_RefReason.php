<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$action == 'create' ? $this->title = 'Создание новой сути обращения' : $this->title = 'Редактирование сути обращения' ;

$this->params['breadcrumbs'][] = ['label' => 'Сути обращения', 'url' => ['/settings/reasons']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h3><?= $this->title.' '.$model->reason_text ?></h3>
    </div>    
    <div class="col-lg-6">
    <?php

    $form = ActiveForm::begin([
        //'id' => 'queue-form',
        'action' => $action == 'create' ? ['settings/reason-create'] : ['settings/reason-update','id' => $model->reason_id ]
    ]);
    
    ?>
    <?= $form->field($model, 'reason_id')->hiddenInput() ?>    
        
    <?= $form->field($model, 'kind_ref_id')
             ->widget(Select2::className(),
                    [
                     'data' => ArrayHelper::map( $modelKind , 'ref_id','text'),
                     'options' => ['placeholder' => 'Классификатор'],
                        'pluginOptions' => [
                            'allowClear' => true
                         ],
                    ]) ?> 
        
    <?= $form->field($model, 'reason_text')->textInput()->hint('Укажите название сути') ?>
    <?= $form->field($model, 'reason_code')->textInput()->hint('Укажите код сути') ?>
    
        
    <?= Html::a('Вернуться',['settings/reasons'],['class'=>'btn']) ?>
      
    <?php
        if (!is_null($model->reason_id)) {
            echo Html::a('Удалить',['settings/reason-delete', 'id' => $model->reason_id],['class'=>'btn btn-default']);
        }
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать суть', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
        
    <?php ActiveForm::end() ?>
    </div>
</div>