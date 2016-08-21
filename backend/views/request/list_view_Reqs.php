<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\grid\GridView;

use yii\widgets\ActiveForm;
use yii\widgets\ListView;

use kartik\date\DatePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;

$this->title = 'Список обращений';
$this->params['breadcrumbs'][] = $this->title;


$filter_count = Yii::$app->session->get('filter_count');

?>

<div class="site-index">

  <!-- Modals -->
  <?php $form = ActiveForm::begin([
                   'action' => ['list'],
                   'method' => 'get',
               ]); ?>
  <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Укажите фильтры</h4>
        </div>
        <div class="modal-body">
          <div class="request-search">
            <div class="row">
              <div class="col-md-6">
                <?= $form->field($searchModel, 'from_date')->label('Дата начала')
                    ->widget(DatePicker::className(),
                        [
                          //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                          'options' => [
                                         'placeholder' => 'ДД.ММ.ГГГГ',
                                       ] ,
                            'pluginOptions' => [
                                'autoclose' => true,
                                //'format' => 'yyyy-mm-dd',
                                'format' => 'dd.mm.yyyy',
                                'todayHighlight' => true
                            ]
                        ])
                ?>
              </div>
              <div class="col-md-6">
                <?= $form->field($searchModel, 'to_date')->label('Дата окончания')
                    ->widget(DatePicker::className(),
                        [
                          //'type' => DatePicker::TYPE_COMPONENT_APPEND,
                          'options' => [
                                         'placeholder' => 'ДД.ММ.ГГГГ',
                                       ] ,
                            'pluginOptions' => [
                                'autoclose' => true,
                                //'format' => 'yyyy-mm-dd',
                                'format' => 'dd.mm.yyyy',
                                'todayHighlight' => true
                            ]
                        ])
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <?= $form->field($searchModel, 'company_id')
                         ->dropDownList(ArrayHelper::map( $modelCompany , 'company_id' , 'company_name'),
                               ['prompt' => '- Зона ответственности -']) ?>
              </div>
              <div class="col-md-6">
                <?= $form->field($searchModel, 'created_by')
                         ->dropDownList(ArrayHelper::map( $modelUser , 'user_id' , 'user_name'),
                               ['prompt' => '- Пользователь системы -']) ?>

              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <?= $form->field($searchModel, 'status_ref_id')
                         ->dropDownList(ArrayHelper::map( $modelStatus , 'ref_id' , 'text'),
                               ['prompt' => '- Статус обращения - ']) ?>
              </div>
              <div class="col-md-6">
                <?= $form->field($searchModel, 'form_ref_id')
                         ->dropDownList(ArrayHelper::map( $modelForm , 'ref_id' , 'text'),
                               ['prompt' => '- Форма обращения - ']) ?>
              </div>
            </div>

            <?= $form->field($searchModel, 'way_ref_id')
                     ->dropDownList(ArrayHelper::map( $modelWay , 'ref_id' , 'text'),
                           ['prompt' => '- Путь поступления - ']) ?>

            <?= $form->field($searchModel, 'kind_ref_id')
                     ->dropDownList(ArrayHelper::map( $modelKind , 'ref_id' , 'text'),
                           ['id' => 'kind_ref_id',
                            'prompt' => '- Вид обращения - ']) ?>

            <?= $form->field($searchModel, 'reason_id')
                     ->widget(DepDrop::classname(), [
                             'type'=>DepDrop::TYPE_SELECT2,
                             //'options' => ['id'=>'reason_id','prompt' => '- Укажите суть обращения -'],
                             'data' => ArrayHelper::map( $modelReason , 'reason_id','reason_text'),
                             'pluginOptions'=>[
                                   'depends'=>['kind_ref_id'],
                                   'placeholder' => '- Укажите суть обращения -',
                                   'url' => yii\helpers\Url::to(['/request/subreason']),
                                  // 'initialize' => true
                             ],
                     ]); ?>
          </div>
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <?= Html::submitButton('Применить фильтры', ['class' => 'btn btn-primary']) ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php ActiveForm::end(); ?>

  <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Выберите отчет</h4>
        </div>
        <div class="modal-body">
          Здесь список отчетов
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
          <button type="button" class="btn btn-primary">Сформировать файл</button>
        </div>
      </div>
    </div>
  </div>
 <!-- END  Modals -->
    <div class="row">
            <div class="col-md-6">
            <?= Html::a('<span class="glyphicon glyphicon-th-large"> </span> Добавить обращение', ['/request/form'],
                        ['class'=>'btn btn-primary',
                         'style' => 'margin-bottom: 15px;']) ?>
            </div>

            <div class="col-md-6" style="text-align: right;">
              <button type="button" class="btn <?= $filter_count == 0 ? 'btn-primary' : "btn-success" ?>" data-toggle="modal" data-target="#filterModal">
              <?= $filter_count == 0 ? '' : "(".$filter_count.")" ?> <span class="glyphicon glyphicon-filter"> </span> Найти по параметрам
              </button>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printModal">
                <span class="glyphicon glyphicon-print"> </span> Сформировать отчет
              </button>
            </div>

            <div class="col-lg-12">
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
