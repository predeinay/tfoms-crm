<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Справочник организаций';
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
               
               <?= Html::a('Добавить организацию', ['/settings/company-form'], 
                         ['class'=>'btn btn-primary',
                          'style'=>'margin-top: 15px; margin-bottom: 15px;']) ?>
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
                                                    ['settings/company-form', 'id' => $model->company_id]
                                                 );
                                }
                            ],
                        'template'=>'{update}' 
                    ],
                    [   'attribute' => 'company_name',
                        'label' => 'Наименование',
                    ],
                    [   'attribute' => 'company_code',
                        'label' => 'Код компании',
                    ],
                    [   'attribute' => 'ref_common.text',
                        'label' => 'Тип организации',
                    ],
                ]
                ]);
                ?>
               
               
            </div>
        </div>
</div>

    
