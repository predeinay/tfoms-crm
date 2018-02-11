<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;

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
    <?= $form->field($model, 'date_start')->widget(DatePicker::className(),
                    [
                      'options' => [
                                     'placeholder' => 'ДД.ММ.ГГГГ',
                                     'value' => $model->date_start?
                                            Yii::$app->formatter->asDate( $model->date_start ,'php:d.m.Y'):null,
                                   ] ,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true
                        ]
                    ])
            ?>
    <?= $form->field($model, 'date_end')->widget(DatePicker::className(),
                    [
                      'options' => [
                                     'placeholder' => 'ДД.ММ.ГГГГ',
                                     'value' => $model->date_end?
                                            Yii::$app->formatter->asDate( $model->date_end ,'php:d.m.Y'):null,
                                   ] ,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true
                        ]
                    ])
            ?>
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