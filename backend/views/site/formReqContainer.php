<?php

use kartik\tabs\TabsX;

$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Home',
        'content'=>'$content1',
        'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Profile',
        'content'=>'$content2',
        'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/main-form'])]
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Dropdown',
        'items'=>[
             [
                 'label'=>'Option 1',
                 'encode'=>false,
                 'content'=>'$content3',
             ],
             [
                 'label'=>'Option 2',
                 'encode'=>false,
                 'content'=>'$content4',
             ],
        ],
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-king"></i> Disabled',
        'headerOptions' => ['class'=>'disabled']
    ],
];


?>

<div class="req-form">
    <div class="row">
        <div class="col-lg-12">
        <?php
        echo TabsX::widget([
                        'items'=>$items,
                        'position'=>TabsX::POS_ABOVE,
                        'encodeLabels'=>false
                    ]);
        ?>    
        </div>
    </div>
</div>