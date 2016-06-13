<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Общие справочники';
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
               
               <?= Html::a('Добавить значение', ['/settings/common-form'], 
                         ['class'=>'btn btn-primary',
                          'style'=>'margin-top: 15px; margin-bottom: 15px;']) ?>
            </div>
            <div class="col-lg-12">
               <?= yii\grid\GridView::widget([
                'dataProvider' => $provider,
                'filterModel' => $searchModel,
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                             'update' => function ($url, $model, $key) {
                                   return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['settings/common-form', 'id' => $model->ref_id]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [   'attribute' => 'type',
                        'label' => 'Наименование справочника',
                        'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'type',
                                        $typeArr,
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Наименование справочника'
                                        ]
                                ),
                    ],
                    [   'attribute' => 'text',
                        'label' => 'Описание',
                    ],
                ]
                ]);
                ?>
               
               
            </div>
        </div>
</div>

    
