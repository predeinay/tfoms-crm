<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use kartik\date\DatePicker;

$this->title = 'Список обращений';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-index">
    <div class="row">
            <div class="col-lg-12">

            <?= Html::a('Добавить обращение', ['/request/form'], 
                        ['class'=>'btn btn-primary',
                         'style' => 'margin-bottom: 15px;']) ?>
            
            <?php
              $searchDay = '';
              if ( key_exists('RequestsSearch', $_GET) ) {
                  $searchDay = $_GET['RequestsSearch']['created_on'];
              }
            ?>
                
            <?php
            
            echo ListView::widget([
                    'dataProvider' => $provider,
                    'itemView' => 'row_View_Req',
                    ]);
            
            ?>
                
            <?php /* GridView::widget([
                'dataProvider' => $provider,
                'filterModel' => $searchModel,
                'rowOptions' => function ($model, $key, $index, $grid) {
                                    if ($model['status_text'] == 'В работе') {
                                        return ['class' => 'grid-yellow'];
                                    }
                                },
                'columns' => [
                    [ 
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                             'update' => function ($url, $model, $key) {
                                   return Html::a('<i class="glyphicon glyphicon-pencil"></i>',  
                                                    ['request/form', 'id' => $model['req_id']]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [
                        'attribute' => 'created_on',
                        'label' => 'Дата',
                        'contentOptions' => ['class' => 'text-wrap', 'style' => 'min-width:95px;'],
                        'filter' => DatePicker::widget([
                                        'options' => [ 'placeholder' => 'Дата обращения', 'class' => 'form-control'],
                                        'name' => 'RequestsSearch[created_on]',
                                        'type' => DatePicker::TYPE_INPUT,//::TYPE_COMPONENT_APPEND,
                                        'removeButton' => false,
                                        'value' => $searchDay,
                                        'pluginOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd',
                                        ]
                                    ]),
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
                        //'contentOptions' => ['class' => 'text-wrap']  
                    ],
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
                        'contentOptions' => ['class' => 'text-wrap', 'style' => 'min-width: 400px;']  
                    ],
                    [   
                        'attribute' => 'status_text',
                        'label' => 'Статус',
                        'content' => function($model, $key, $index, $column) {
                                    return $model['status_text'] == 'закрыто'?
                                             '<b><span class="label label-default">'.$model['status_text'].'</span></b>':
                                             '<b><span class="label label-warning">'.$model['status_text'].'</span></b>';
                                },
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
             */

                ?>
            
            
            </div>
    </div>
</div>