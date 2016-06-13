<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Список обращений';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <div class="row">
            <div class="col-lg-12">

            <?= Html::a('Добавить обращение', ['/site/form'], 
                        ['class'=>'btn btn-primary',
                         'style' => 'margin-bottom: 15px;']) ?>

            
            <?= GridView::widget([
                'dataProvider' => $provider,
                'filterModel' => $searchModel,
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                             'update' => function ($url, $model, $key) {
                                   return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['site/form', 'id' => $model['req_id']]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [
                        'attribute' => 'created_on',
                        'label' => 'Дата',
                        'contentOptions' => ['class' => 'text-wrap'],
                    ],
                    [
                        'attribute' => 'fio_polis',
                        'label' => 'ФИО и полис',
                        'content' => function($model) {
                                return $model['surname']. ' ' .
                                       $model['name']. ' ' .
                                       $model['patronymic'].'<br>'.
                                       ($model['policy_ser'] == '' ? '' : 'Серия ').$model['policy_ser'].'<br>'.
                                       ($model['policy_num'] == '' ? '' : 'Номер ').$model['policy_num']
                                ;
                        },
                        'contentOptions' => ['class' => 'text-wrap']  
                    ],
                    /*[   'attribute' => 'way_text',
                        'label' => 'Путь пост.',
                        'contentOptions' => ['class' => 'text-wrap']  
                    ],*/
                    [   'attribute' => 'kind_text',
                        'label' => 'Вид',
                        'contentOptions' => ['class' => 'text-wrap'],
                        'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'kind_ref_id',
                                        yii\helpers\ArrayHelper::map(common\models\refCommon::getRefByName('Вид обращения'),'ref_id','text'),
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Вид'
                                        ]
                                ),
                    ],
                   /* [   'attribute' => 'form_text',
                        'label' => 'Форма',
                        'contentOptions' => ['class' => 'text-wrap']  
                    ],*/
                    [   
                        'attribute' => 'reason_text',
                        'label' => 'Суть',
                        'contentOptions' => ['class' => 'text-wrap'],
                        'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'reason_id',
                                        yii\helpers\ArrayHelper::map(common\models\refReason::getAll(),'reason_id','reason_text'),
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Суть обращения'
                                        ]
                                ),

                    ],
                    [
                        'attribute' => 'note',
                        'label' => 'Описание обращения',
                        'contentOptions' => ['class' => 'text-wrap']  
                    ],
                    [   
                        'attribute' => 'status_text',
                        'label' => 'Статус',
                        'filter' => Html::activeDropDownList(
                                        $searchModel,
                                        'status_ref_id',
                                        yii\helpers\ArrayHelper::map(common\models\refCommon::getRefByName('Статус обращения'),'ref_id','text'),
                                        [
                                            'class'=>'form-control',
                                            'prompt' => ' - Статус'
                                        ]
                                ),
                    ],
                ]
                ]);
                ?>
            
            
            </div>
    </div>
</div>