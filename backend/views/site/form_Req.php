<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\DateTimePicker;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;


use yii\bootstrap\Nav;

$action == 'create' ? $this->title = 'Создание нового обращения' : $this->title = 'Редактирование обращения' ;

$this->params['breadcrumbs'][] = ['label' => 'Список обращения', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">

<div class="row">
    <div class="col-lg-12">
        <!--h3><?= $this->title.' '.$model->req_id ?></h3-->
        <h3><?= $this->title.' '.(isset($model->req_id) ? '#'.$model->req_id :'') ?></h3>
    </div>    
    
    <?php
    /*if (isset($model->req_id))
    echo Nav::widget([
                'items' => [
                    [ 
                      'label' => 'Основная информация',
                      'url' => ['/site/form'],
                    ],
                    [ 
                      'label' => 'Комментарии',
                      'url' => ['/site/error'],
                    ],
                    [ 
                      'label' => 'Звонки',
                      'url' => ['/site/error'],
                    ],
                ],
    
                'options' => ['class' =>'nav-tabs']
     ]);
    */
    $form = ActiveForm::begin([
        'action' => $action == 'create' ? ['site/create'] : ['site/update','id' => $model->req_id ]
    ]);
    ?>
    
    <?= $form->field($model, 'req_id')->hiddenInput() ?>
    
    <div class="col-sm-5">
    
    <?= $form->field($model, 'created_on')
              ->widget(DateTimePicker::className(),
                [
                  //'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                  'options' => [
                                // 'value' =>  Yii::$app->formatter->asDate($model->created_on,'yyyy-MM-dd HH:mm'),
                                 'placeholder' => 'ГГГГ-ММ-ДД ЧЧ:ММ:СС',
                               ] ,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii:ss',
                        'todayHighlight' => true
                    ]
                ])
    ?>

    <?= $form->field($model, 'form_ref_id')
             ->dropDownList(ArrayHelper::map( $modelForm , 'ref_id' , 'text'),
                            ['prompt' => '- Укажите форму обращения - ']) ?>    
        
    <?= $form->field($model, 'way_ref_id')
             ->dropDownList(ArrayHelper::map( $modelWay , 'ref_id' , 'text'),
                             ['prompt' => '- Укажите путь поступления -']) ?> 

    <?= $form->field($model, 'kind_ref_id')
             ->dropDownList(ArrayHelper::map( $modelKind,'ref_id','text'),
                            [ 'id' => 'kind_ref_id',
                             'prompt' => '- Укажите вид обращения -']) ?> 
        
    <?= $form->field($model, 'reason_id')
             ->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    //'options' => ['id'=>'reason_id','prompt' => '- Укажите суть обращения -'],
                    'data' => ArrayHelper::map( $modelReason , 'reason_id','reason_text'),
                    'pluginOptions'=>[
                        'depends'=>['kind_ref_id'],
                        'placeholder' => '- Укажите суть обращения -',
                        'url' => yii\helpers\Url::to(['/site/subreason']),
                       // 'initialize' => true
                    ]
                ]); ?>
    
    <?= $form->field($model, 'result_ref_id')
             ->widget(DepDrop::classname(), [
                    //'type'=>DepDrop::TYPE_SELECT2,
                    'options' => [ 'prompt' => '- Укажите результат -'],
                    'data' => ArrayHelper::map( $modelResult , 'ref_id','text'),
                    'pluginOptions'=>[
                        'depends'=>['kind_ref_id'],
                        'placeholder' => '- Укажите результат -',
                        'url' => yii\helpers\Url::to(['/site/subresult']),
                        //'initialize' => true
                    ]
                ]); ?>
        
    <?= $form->field($model, 'status_ref_id')
             ->dropDownList(ArrayHelper::map( $modelStatus , 'ref_id' , 'text'),
                            ['prompt' => '- Укажите статус - ']) ?>    
        
    </div>
    <div class="col-sm-7">
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'surname')->textInput(['placeholder' => 'Укажите фамилию']) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Укажите отчество']) ?>  
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'patronymic')->textInput(['placeholder' => 'Укажите имя']) ?>  
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
        <?= $form->field($model, 'birth_day')
            ->widget(DatePicker::className(),
                [
                  //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                  'options' => [
                                 'placeholder' => 'ГГГГ-ММ-ДД',
                               ] ,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ])
        ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'policy_num')->textInput(['placeholder' => 'Номер полиса страхования']) ?>  
        </div>
        <div class="col-sm-4">    
            <?= $form->field($model, 'policy_ser')->textInput(['placeholder' => 'Серия полиса']) ?>  
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'phone_aoh')->textInput(['placeholder' => 'Автоопределенный номер с АТС']) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'phone_contact')->textInput(['placeholder' => 'Контактный телефон']) ?>
        </div>
    </div>
        
    <?= $form->field($model, 'address')->textarea(
                                ['placeholder' => 'Почтовый адрес места регистрации',
                                 'rows' => 1 ]) 
    ?>
        
    <?= $form->field($model, 'note')->textarea(
                                ['placeholder' => 'Укажите текстовое описание обращения',
                                 'rows' => 3 ]) 
    ?>
        
    <?= $form->field($model, 'final_note')->textarea(
                                ['placeholder' => 'Укажите принятые меры',
                                 'rows' => 3 ]) 
    ?>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
    <?= Html::a('Вернуться',['site/index'],['class'=>'btn']) ?>
    <?php
        if (!is_null($model->req_id)) {
            echo Html::a('Удалить',['site/delete', 'id' => $model->req_id],['class'=>'btn btn-default']);
        }
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать обращение', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
   
        </div>
    </div>
    <?php ActiveForm::end() ?> 
    </div>
</div>