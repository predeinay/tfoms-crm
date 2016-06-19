<?php

$this->title = 'Комментарии к обращению';

$this->params['breadcrumbs'][] = ['label' => 'Список обращений', 'url' => ['/request/list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <?= $this->render('form_Tabs',['req_id' => $req_id ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            
            <?php 
                echo '<pre>';
                var_dump( $commentModels ); 
                echo '</pre>';
            ?>
            
        </div>
    </div>
</div>
