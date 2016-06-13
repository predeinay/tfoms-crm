<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Сути обращения';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-index">
        <div class="row">
            <div class="col-lg-12">
                <?= $this->render('settingsNav') ?>
            </div>
        </div>
    
        <div class="row">
            <div class="col-lg-12">
               
               <?= Html::a('Добавить суть', ['/settings/reason-form'], 
                   ['class'=>'btn btn-primary',
                    'style'=>'margin-top: 15px; margin-bottom: 15px;']) ?>
            </div>
            
            <div class="col-lg-12">
               <?= yii\grid\GridView::widget([
                'dataProvider' => $provider,
                'filterModel' => $filterModel,
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                             'update' => function ($url, $model, $key) {
                                   return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['settings/reason-form', 'id' => $model->reason_id]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [   'attribute' => 'reason_code',
                        'label' => 'Код сути',
                    ],
                    [   'attribute' => 'reason_text',
                        'label' => 'Описание сути',
                    ],
                    [   'attribute' => 'reasonType.text',
                        'label' => 'Вид обращения',
                        'filter' => Html::activeDropDownList(
                                        $filterModel,
                                        'kind_ref_id',
                                        yii\helpers\ArrayHelper::map($kindArrLov,'ref_id','text'),
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Вид обращения'
                                        ]
                                ),
                    ],
                ]
                ]);
                ?>
               
               
            </div>
        </div>
</div>

    
