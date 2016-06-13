<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Справочник пользователей системы';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-index">
        <div class="row">
            
            <div class="col-lg-12">
                <?= $this->render('settingsNav') ?>
            </div>
        
            <div class="col-lg-12">
               <?= Html::a('Добавить пользователя', 
                            ['/settings/user-form'], 
                            ['class'=>'btn btn-primary', 'style'=>'margin-top: 15px; margin-bottom: 15px;']
                            ) ?>
            </div>
            
            <div class="col-lg-12">
               
               <?= yii\grid\GridView::widget([
                'dataProvider' => $provider,
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                             'update' => function ($url, $model, $key) {
                                   return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['settings/user-form', 'id' => $model->user_id]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [   'attribute' => 'user_name',
                        'label' => 'Имя пользователя',
                    ],
                    [   'attribute' => 'login',
                        'label' => 'Логин',
                    ],
                ]
                ]);
                ?>
               
            </div>
        </div>
</div>

    
