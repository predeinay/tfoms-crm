<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
                
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadMoModal">
                Загрузить справочник МО
              </button>
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
    
    <!-- Modals -->
<div class="modal fade" id="uploadMoModal" tabindex="-1" role="dialog" aria-labelledby="uploadMoModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Выберите файл</h4>
        </div>
        <div class="modal-body">
          <?= $form->field($uploadModel, 'file')->fileInput()->label(false) ?>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Загрузить</button>
        </div>

        <?php ActiveForm::end() ?>
      </div>
    </div>
  </div>
    
</div>

    
