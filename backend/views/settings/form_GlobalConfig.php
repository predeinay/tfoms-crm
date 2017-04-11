<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$model->config_id ? $this->title = 'Редактирование параметра' : $this->title = 'Создание нового параметра' ;

$this->params['breadcrumbs'][] = ['label' => 'Глобальные настройки', 'url' => ['/settings/global-list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <h3><?= $this->title ?></h3>
    </div>
    <div class="col-lg-6">
    <?php

    $form = ActiveForm::begin([
        'action' => ['settings/global']
    ]);

    ?>
    <?= $form->field($model, 'config_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'param')->textInput()->hint('Параметр') ?>

    <?= $form->field($model, 'value')->textInput()->hint('Значение') ?>

    <?= Html::a('Вернуться',['settings/global-list'],['class'=>'btn']) ?>

    <?php
        if ($model->config_id) {
            echo Html::a('Удалить',['settings/global', 'id' => $model->config_id, 'delete' => true ],['class'=>'btn btn-default']);
        }
    ?>

    <?= Html::submitButton($model->config_id ? 'Сохранить изменения' : 'Создать значение', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>
    </div>
</div>
