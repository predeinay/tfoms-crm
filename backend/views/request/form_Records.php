<?php

$this->title = 'Записи разговоров';

$this->params['breadcrumbs'][] = ['label' => 'Список обращений', 'url' => ['/request/list']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <?= $this->render('form_Tabs',['requestModel' => $requestModel ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <em>В разработке..</em>
        </div>
    </div>
</div>
