<?php

namespace backend\models;

use Yii;
use common\models\Requests;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use PHPExcel;
use PHPExcel_Style_Border;

class requestSearch extends Requests {

  public $status_ref_id;
  public $company_id;
  public $form_ref_id;
  public $way_ref_id;
  public $kind_ref_id;
  public $reason_id;
  public $from_date;
  public $to_date;
  public $created_by;
  public $surname;
  public $name;
  public $patronymic;

  public $filterCount = 0;

  public function rules() {

      return [
          [['status_ref_id',
            'company_id',
            'claim_company_id',
            'form_ref_id',
            'way_ref_id',
            'kind_ref_id',
            'reason_id',
            'created_by',
            'from_date','to_date',
            'surname','name','patronymic',
            'executed_by',
            'result_ref_id',
            'filterCount'], 'safe'],
      ];
  }

  public function printJournal($params) {
      // генерим заголовки
      $xls = new PHPExcel();

      $BStyle = [
        'borders' => [
          'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN
          ]
        ]
      ];

      /*$xls->getDefaultStyle()
        ->getBorders()
        ->getTop()
            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $xls->getDefaultStyle()
        ->getBorders()
        ->getBottom()
            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $xls->getDefaultStyle()
        ->getBorders()
        ->getLeft()
            ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $xls->getDefaultStyle()
        ->getBorders()
        ->getRight()
        ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);*/

      $xls->setActiveSheetIndex(0);
      $sheet = $xls->getActiveSheet();

      $sheet->getRowDimension(1)->setRowHeight(30);

      /*$sheet->getColumnDimension('A')->setAutoSize(true);
      $sheet->getColumnDimension('B')->setAutoSize(true);
      $sheet->getColumnDimension('C')->setAutoSize(true);
      $sheet->getColumnDimension('D')->setAutoSize(true);
      $sheet->getColumnDimension('E')->setAutoSize(true);
      $sheet->getColumnDimension('F')->setAutoSize(true);
      $sheet->getColumnDimension('G')->setAutoSize(true);
      $sheet->getColumnDimension('H')->setAutoSize(true);
      $sheet->getColumnDimension('I')->setAutoSize(true);
      $sheet->getColumnDimension('J')->setAutoSize(true);
      $sheet->getColumnDimension('K')->setAutoSize(true);
      $sheet->getColumnDimension('L')->setAutoSize(true);
      $sheet->getColumnDimension('M')->setAutoSize(true);
      $sheet->getColumnDimension('N')->setAutoSize(true);
      $sheet->getColumnDimension('O')->setAutoSize(true);
      $sheet->getColumnDimension('P')->setAutoSize(true);
      $sheet->getColumnDimension('Q')->setAutoSize(true);
      $sheet->getColumnDimension('R')->setAutoSize(true);
      $sheet->getColumnDimension('S')->setAutoSize(true);*/

      $sheet->setTitle('Электронный журнал');

      $sheet->setCellValue("A1", '№ п\п');
      $sheet->setCellValue("B1", 'Дата');
      $sheet->setCellValue("C1", 'Форма обращения');
      $sheet->setCellValue("D1", 'Путь поступления');
      $sheet->setCellValue("E1", 'Вх.№');
      $sheet->setCellValue("F1", 'Фамилия И.О.');
      $sheet->setCellValue("G1", 'Дата рождения');
      $sheet->setCellValue("H1", 'Почтовый адрес, контактные данные');
      $sheet->setCellValue("I1", 'Территория страхования');
      $sheet->setCellValue("J1", 'Полис ОМС ');
      $sheet->setCellValue("K1", 'Вид обращения');
      $sheet->setCellValue("L1", 'Суть обращения');
      $sheet->setCellValue("M1", 'Код обращения');
      $sheet->setCellValue("N1", 'Наименование МО');
      $sheet->setCellValue("O1", 'Наименование СМО');
      $sheet->setCellValue("P1", 'Уровень рассмотрения');
      $sheet->setCellValue("Q1", 'Исполнитель');
      $sheet->setCellValue("R1", 'Результат обращения');
      $sheet->setCellValue("S1", 'Принятые меры');

      $modelReqs = $this->getDataModel($params)->all();
      $i = 0;
      // для каждой заявки генерим строку в таблицу
      foreach ($modelReqs as $index => $model) {

          $i = $index +2;
          $sheet->setCellValue("A".$i, $model->req_id); //'№ п\п'
          $sheet->setCellValue("B".$i, $model->created_on); // Дата
          $sheet->setCellValue("C".$i, $model->form_text);  // Форма поступления
          $sheet->setCellValue("D".$i, $model->way_text); //Путь поступления
          $sheet->setCellValue("E".$i, $model->req_id);             //Вх.№
          $sheet->setCellValue("F".$i, $model->surname.' '.$model->name.' '.$model->patronymic);     //Фамилия И.О.
          $sheet->setCellValue("G".$i, $model->birth_day);    //Дата рождения
          $sheet->setCellValue("H".$i, $model->address.' аон: '.$model->phone_aoh.' конт.тел: '.$model->phone_contact); //Почтовый адрес, контактные данные
          $sheet->setCellValue("I".$i, '???');
          $sheet->setCellValue("J".$i, '№'.$model->policy_num.' '.$model->policy_ser);
          $sheet->setCellValue("K".$i, $model->kind_text);
          $sheet->setCellValue("L".$i, $model->reason_text); // Суть обращения
          $sheet->setCellValue("M".$i, '-'); //Код обращения
          $sheet->setCellValue("N".$i, '??');  // Наименование МО
          $sheet->setCellValue("O".$i, '??');    // Наименование СМО
          $sheet->setCellValue("P".$i, '??'); //Уровень рассмотрения
          $sheet->setCellValue("Q".$i, $model->user_name); // Кто зарегал
          $sheet->setCellValue("R".$i, $model->result_text); //Результат обращения
          $sheet->setCellValue("S".$i, $model->final_note); // меры
      }

      // set border
      $xls->getActiveSheet()->getStyle('A1:S'.$i)->applyFromArray($BStyle);
      $sheet->getStyle('A1:S'.$i)->getAlignment()->setWrapText(true);
      $sheet->getRowDimension(2)->setRowHeight(-1);

      /*foreach($xls->getActiveSheet()->getRowDimensions() as $rd) {
            $rd->setRowHeight(-1);
        }*/
      //$sheet->getColumnDimension('B')->setAutoSize(true);

      return $xls;

  }

  public function search($params) {

            $provider = new ActiveDataProvider([
                                    'query' => $this->getDataModel($params),
                                    'pagination' => [
                                        'pageSize' => 20,
                                    ],
                                ]);

    return $provider;
  }

  public function getDataModel($params) {

          $reqModel = Requests::find()
                ->select('req_id, created_on, user_name, company_name, status.text as status_text,
                          surname, name, patronymic, address,
                          note,
                          result.text as result_text,
                          form.text as form_text,
                          way.text as way_text,
                          kind.text as kind_text,
                          ref_reasons.reason_text
                          ')
                ->innerJoin('ref_users', 'requests.created_by = ref_users.user_id')
                ->leftJoin('ref_company', 'requests.company_id = ref_company.company_id')
                ->innerJoin('ref_common status', 'requests.status_ref_id = status.ref_id' )
                ->innerJoin('ref_common form', 'requests.form_ref_id = form.ref_id' )
                ->innerJoin('ref_common way', 'requests.way_ref_id = way.ref_id' )
                ->innerJoin('ref_common kind', 'requests.kind_ref_id = kind.ref_id' )
                ->leftJoin('ref_common result', 'requests.result_ref_id = result.ref_id' )
                ->innerJoin('ref_reasons', 'requests.reason_id = ref_reasons.reason_id' )

                ->orderBy('created_on desc')
                ;

            if ( Yii::$app->user->identity->isTfomsRole( Yii::$app->user->identity->id ) ) {}
            else {
                $reqModel->where(['requests.company_id' => Yii::$app->user->identity->company_id]);
            }

            // load the search form data and validate
            if ( $this->load($params) && $this->validate() ) {
                $this->filterCount = count(array_filter( $params['requestSearch'] ));
                Yii::$app->session->set('request_search_model_filter_count',$this->filterCount);
                Yii::$app->session->set('request_search_model',$params);
            } else {
                $this->load( Yii::$app->session->get('request_search_model') );
                $this->filterCount = Yii::$app->session->get('request_search_model_filter_count');

            }
            
            /*echo "<pre>";
            var_dump($params['requestSearch']);
            echo "</pre>";*/
            //exit();
            //array_filter($params['requestSearch'], function($x) { return !empty($x); });


            $reqModel->andFilterWhere(['requests.company_id' =>  $this->company_id]);
            $reqModel->andFilterWhere(['requests.claim_company_id' =>  $this->claim_company_id]);
            $reqModel->andFilterWhere(['requests.status_ref_id' =>  $this->status_ref_id]);
            $reqModel->andFilterWhere(['requests.form_ref_id' =>  $this->form_ref_id]);
            $reqModel->andFilterWhere(['requests.way_ref_id' =>  $this->way_ref_id]);
            $reqModel->andFilterWhere(['requests.kind_ref_id' =>  $this->kind_ref_id]);
            $reqModel->andFilterWhere(['requests.reason_id' =>  $this->reason_id]);
            $reqModel->andFilterWhere(['requests.result_ref_id' =>  $this->result_ref_id]);
            $reqModel->andFilterWhere(['requests.created_by' =>  $this->created_by]);

            $reqModel->andFilterWhere(['requests.executed_by' =>  $this->executed_by]);

            $reqModel->andFilterWhere(['like','requests.name', $this->name ]);
            $reqModel->andFilterWhere(['like','requests.patronymic', $this->patronymic]);
            $reqModel->andFilterWhere(['like','requests.surname', $this->surname]);
              //var_dump( $reqModel->createCommand()->getRawSql() );
              //exit();
            if ( $this->from_date != '') {
              $reqModel->andFilterWhere([ '>=','DATE(requests.created_on)' ,\Yii::$app->myhelper->to_date($this->from_date) ]);
            }
            if ( $this->to_date != '') {
              $reqModel->andFilterWhere([ '<=','DATE(requests.created_on)' ,\Yii::$app->myhelper->to_date($this->to_date) ]);
            }

    return $reqModel;
  }

  public static function clearSessionFilter() {
    Yii::$app->session->set('request_search_model','');
    Yii::$app->session->set('request_search_model_filter_count','');
  }
}
