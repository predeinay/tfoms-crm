<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$action == 'create' ? $this->title = 'Создание новой организации' : $this->title = 'Редактирование организации' ;

$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['/settings/company']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h3><?= $this->title.' '.$model->company_name ?></h3>
    </div>    
    <div class="col-lg-6">
    <?php

    $form = ActiveForm::begin([
        'action' => $action == 'create' ? ['settings/company-create'] : ['settings/company-update','id' => $model->company_id ]
    ]);
    
    ?>
    <?= $form->field($model, 'company_id')->hiddenInput() ?>    
        
    <?= $form->field($model, 'type_ref_id')
             ->widget(Select2::className(),
                    [
                     'data' => ArrayHelper::map( $modelType , 'ref_id','text'),
                     'options' => ['placeholder' => 'Классификатор'],
                        'pluginOptions' => [
                            'allowClear' => true
                         ],
                    ]) ?> 
        
    <?= $form->field($model, 'company_name')->textInput()->hint('Укажите название организации') ?>
    <?= $form->field($model, 'company_short_name')->textInput()->hint('Укажите краткое название организации') ?>
    <?= $form->field($model, 'company_code')->textInput()->hint('Укажите код организации') ?>
    
        
    <?= Html::a('Вернуться',['settings/company'],['class'=>'btn']) ?>
      
    <?php
        if (!is_null($model->company_id)) {
            echo Html::a('Удалить',['settings/company-delete', 'id' => $model->company_id],['class'=>'btn btn-default']);
        }
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать организацию', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
        
    <?php ActiveForm::end() ?>
    </div>
</div>