<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$action == 'create' ? $this->title = 'Создание нового пользователя' : $this->title = 'Редактирование пользователя' ;

$this->params['breadcrumbs'][] = ['label' => 'Справочник пользователей системы', 'url' => ['/settings/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h3><?= $this->title.' '.$model->user_name ?></h3>
    </div>    
    <div class="col-lg-6">
    <?php

    $form = ActiveForm::begin([
        //'id' => 'queue-form',
        'action' => $action == 'create' ? ['settings/user-create'] : ['settings/user-update','id' => $model->user_id ]
    ]);
    
    ?>
    <?= $form->field($model, 'user_id')->hiddenInput() ?>    
    <?= $form->field($model, 'user_name')->textInput()->hint('Укажите имя пользователя') ?>
    <?= $form->field($model, 'login')->textInput()->hint('Укажите логин пользователя') ?>
        
    <?= $form->field($model, 'company_id')
             ->dropDownList(ArrayHelper::map($companyModel, 'company_id', 'company_name'),
                     ['prompt' => ' - Укажите организацию'])
             ->hint('Выберите организацию из списка') ?>
        
    <?= $form->field($model, 'password')->textInput()->hint('Укажите пароль для входа в систему') ?>

    <?= Html::a('Вернуться',['settings/index'],['class'=>'btn']) ?>
      
    <?php
        if (!is_null($model->user_id)) {
            echo Html::a('Удалить',['settings/user-delete', 'id' => $model->user_id],['class'=>'btn btn-default']);
        }
    ?>    
        
    <?php
    if ($action == 'create') {
        echo Html::submitButton('Создать пользователя', ['class' => 'btn btn-primary']); 
    }
      else {
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
    }
    
    ?>
        
    <?php ActiveForm::end() ?>
    </div>
</div>