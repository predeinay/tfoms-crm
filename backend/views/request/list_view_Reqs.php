<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use yii\grid\GridView;

use yii\widgets\ActiveForm;
use yii\widgets\ListView;

use kartik\date\DatePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;

use backend\models\request\RequestSearchReport;

$this->title = 'Список обращений';
$this->params['breadcrumbs'][] = $this->title;


//$filter_count = Yii::$app->session->get('filter_count');

?>

<div class="site-index">

  <!-- Modals -->
  <?php $form = ActiveForm::begin([
                   'action' => ['list'],
                   'method' => 'get',
               ]); ?>
  <div class="modal fade" id="filterModal" role="dialog" aria-labelledby="filterModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Укажите фильтры</h4>
        </div>
        <div class="modal-body" style="overflow:hidden;">
          <div class="request-search">
            <div class="row">
              <div class="col-md-4">
                <?= $form->field($searchModel, 'surname')->textInput(['placeholder' => 'Укажите фамилию']) ?>
              </div>
              <div class="col-md-4">
                <?= $form->field($searchModel, 'name')->textInput(['placeholder' => 'Укажите имя']) ?>
              </div>
              <div class="col-md-4">
                <?= $form->field($searchModel, 'patronymic')->textInput(['placeholder' => 'Укажите отчество']) ?>
              </div>
            </div>
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
                <?php
                echo $form->field($searchModel, 'company_id')
                         ->widget(Select2::classname(), [
                                'options' => ['placeholder' => '- Зона ответственности -'],
                                'pluginOptions' => [ 'allowClear' => true, ],
                                //'options' => ['id'=>'reason_id','prompt' => '- Укажите суть обращения -'],
                                'data' => ArrayHelper::map( $modelCompany , 'company_id','company_name'),
                            ]);
                /*echo $form->field($searchModel, 'company_id')
                         ->dropDownList(ArrayHelper::map( $modelCompany , 'company_id' , 'company_name'),
                               ['prompt' => '- Зона ответственности -']);*/
                ?>
              </div>
              <div class="col-md-6">
                <?php
                echo $form->field($searchModel, 'claim_company_id')
                         ->widget(Select2::classname(), [
                                'options' => ['placeholder' => '- Организация на которую жалуются -'],
                                'pluginOptions' => [ 'allowClear' => true, ],
                                //'options' => ['id'=>'reason_id','prompt' => '- Укажите суть обращения -'],
                                'data' => ArrayHelper::map( $modelCompany , 'company_id','company_name'),
                            ]);
                /*echo $form->field($searchModel, 'claim_company_id')
                         ->dropDownList(ArrayHelper::map( $modelCompany , 'company_id' , 'company_name'),
                               ['prompt' => '- Организация на которую жалуются -']);*/
                ?>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <?= $form->field($searchModel, 'created_by')
                         ->widget(Select2::classname(), [
                                'options' => ['placeholder' => 'Пользователь системы'],
                                'pluginOptions' => [ 'allowClear' => true, ],
                                //'options' => ['id'=>'reason_id','prompt' => '- Укажите суть обращения -'],
                                'data' => ArrayHelper::map( $modelUser , 'user_id','user_name'),
                            ]);
                ?>

                <?php /*$form->field($searchModel, 'created_by')
                         ->dropDownList(ArrayHelper::map( $modelUser , 'user_id' , 'user_name'),
                               ['prompt' => '- Пользователь системы -'])*/
                        ?>

              </div>

              <div class="col-md-6">
                <?= $form->field($searchModel, 'executed_by')
                         ->widget(Select2::classname(), [
                                'options' => ['placeholder' => 'Укажите исполнителя'],
                                'pluginOptions' => [ 'allowClear' => true, ],
                                //'options' => ['id'=>'reason_id','prompt' => '- Укажите суть обращения -'],
                                'data' => ArrayHelper::map( $modelExecutor , 'user_id','user_name'),
                            ]);
                ?>
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
                             'options' => ['prompt' => '- Укажите суть обращения -'],
                             'data' => ArrayHelper::map( $modelReason , 'reason_id','reason_text'),
                             'pluginOptions'=>[
                                   'allowClear' => true,
                                   'depends'=>['kind_ref_id'],
                                   'placeholder' => '- Укажите суть обращения -',
                                   'url' => yii\helpers\Url::to(['/request/subreason']),
                                  // 'initialize' => true
                             ],
                     ]); ?>

            <?= $form->field($searchModel, 'result_ref_id')
             ->widget(DepDrop::classname(), [
                    //'type'=>DepDrop::TYPE_SELECT2,
                    'options' => [ 'prompt' => '- Укажите результат -'],
                    'data' => ArrayHelper::map( $modelResult , 'ref_id','text'),
                    'pluginOptions'=>[
                        'depends'=>['kind_ref_id'],
                        'placeholder' => '- Укажите результат -',
                        'url' => yii\helpers\Url::to(['/request/subresult']),
                        //'initialize' => true
                    ]
                ]);  ?>
          </div>
        </div>
        <div class="modal-footer">
          <div class="form-group">
            <?= Html::submitButton('Применить фильтры', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить фильтры',['request/filter-clear'],[ 'class' => 'btn btn-default']) ?>
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
            <div class="list-group">
              <a href="<?= \yii\helpers\Url::to(['request/report','reportType' => RequestSearchReport::SIMPLE_JOURNAL]) ?>" class="list-group-item"><span class="glyphicon glyphicon-list-alt"> </span> Электронный журнал</a>
              <!--a href="#" class="list-group-item"><span class="glyphicon glyphicon-stats"> </span> Форма №2 по приказу 146</a-->
              <?= Html::a('<span class="glyphicon glyphicon-stats"> </span> Сводный отчет в разрезе причин обращений',
                        ['request/report','reportType' => RequestSearchReport::PIVOT_BY_REASON],
                        ['class'=>'list-group-item']) ?>
            </div>
        </div>
        <!--div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
          <button type="button" class="btn btn-primary">Сформировать файл</button>
        </div-->
      </div>
    </div>
  </div>
 <!-- END  Modals -->
    <div class="row">
            <div class="col-lg-12" style="margin-top: 5px;">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">
              <span class="glyphicon glyphicon-filter"> </span>
              <?php
                echo $searchModel->filterCount == 0 ? '' : '<span class="badge">'.$searchModel->filterCount.'</span>';
                //echo $filter_count == 0 ? '' : '<span class="badge">'.$filter_count.'</span>';
              ?>
              </button>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printModal">
                <span class="glyphicon glyphicon-print"> </span>
              </button>
            <?= Html::a('<span class="glyphicon glyphicon-plus"> </span> Добавить обращение', ['/request/form'],
                        ['class'=>'btn btn-primary']) ?>
            </div>

          </div>
            <div class="row">
            <div class="col-lg-12" style="margin-top: 15px;">
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

            </div>
    </div>
</div>
