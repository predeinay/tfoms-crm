<?php

namespace backend\models\request;
use Yii;
use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Writer_Excel5;
use Exception;

class RequestSearchReport extends RequestSearch {

  const SIMPLE_JOURNAL = 'simpleJournal';
  const PIVOT_BY_REASON = 'pivotByReason';

  private $reportType;
  private $xls;
  private $fileName;

  function __construct($reportType) {
       $this->reportType = $reportType;
  }

  public function prepareReport($searchModel) {
    if (method_exists($this,$this->reportType )) {
      $method = $this->reportType;
      $this->$method($searchModel);
    } else {
      throw new Exception("Report type not found in Report Object", 1);
    }
   }

   private function pivotByReason($searchModel) {
     $this->xls = new PHPExcel();
     $this->fileName = 'Обращения_свод_по_причинам_'.date('l jS \of F Y h:i:s A').'.xls';
     $BStyle = [
       'borders' => [
         'allborders' => [
           'style' => PHPExcel_Style_Border::BORDER_THIN
         ]
       ]
     ];

     $this->xls->setActiveSheetIndex(0);
     $sheet = $this->xls->getActiveSheet();

     // header
     $sheet->getRowDimension(1)->setRowHeight(30);
     $sheet->setTitle('ОБРАЩЕНИЯ ЗАСТРАХОВАННЫХ ЛИЦ');
     $sheet->setCellValue("A1", 'ОБРАЩЕНИЯ ЗАСТРАХОВАННЫХ ЛИЦ');
     $sheet->mergeCells("A1:I1");
     $sheet->setCellValue("A2", 'Виды обращений');
     $sheet->setCellValue("B2", '№ стр.');
     $sheet->setCellValue("C2", 'Количество поступивших обращений за отчетный период');
     $sheet->mergeCells("A2:A4");
     $sheet->mergeCells("B2:B4");
     $sheet->mergeCells("C2:I2");
     $sheet->setCellValue("C3", 'ТФОМС');
     $sheet->mergeCells("C3:E3");
     $sheet->setCellValue("F3", 'СМО');
     $sheet->mergeCells("F3:H3");
     $sheet->setCellValue("C4", 'Устных');
     $sheet->setCellValue("D4", 'Письменных');
     $sheet->setCellValue("E4", 'Всего');
     $sheet->setCellValue("F4", 'Устных');
     $sheet->setCellValue("G4", 'Письменных');
     $sheet->setCellValue("H4", 'Всего');
     $sheet->setCellValue("I3", 'Итого');
     $sheet->mergeCells("I3:I4");
     $sheet->setCellValue("A5", '1');$sheet->setCellValue("B5", '2');$sheet->setCellValue("C5", '3');
     $sheet->setCellValue("D5", '4');$sheet->setCellValue("E5", '5');$sheet->setCellValue("F5", '6');
     $sheet->setCellValue("G5", '7');$sheet->setCellValue("H5", '8');$sheet->setCellValue("I5", '9');
     $i = 5;
     $sql = "
      SELECT kind.text kind_text,
             rr.reason_text,
             rr.reason_code,
             sum(CASE WHEN form.text = 'устно' then 1 else 0 END) verbally_count,
             sum(CASE WHEN form.text = 'письменно' then 1 else 0 END) writing_count,
             sum(CASE WHEN form.text = 'устно' then 1 else 0 END) verbally_count,
             sum(CASE WHEN form.text = 'письменно' then 1 else 0 END) writing_count,
             count(*) cc
        FROM requests r inner join ref_reasons rr on r.reason_id = rr.reason_id
                        inner join ref_common kind on rr.kind_ref_id = kind.ref_id
                        inner join ref_common form on r.form_ref_id = form.ref_id
       group by rr.kind_ref_id, kind.text, r.reason_id, rr.reason_text, rr.reason_code
       order by kind_text, CONVERT(rr.reason_code,UNSIGNED INTEGER)";

       $data = Yii::$app->db->createCommand($sql)->queryAll();
       foreach ($data as $index => $row) {
         $i++;
         //$index+6;
         $sheet->setCellValue("A".($index+6),$row['reason_text'] );
         $sheet->setCellValue("B".($index+6),$row['reason_code'] );
         $sheet->setCellValue("C".($index+6),$row['verbally_count'] );
         $sheet->setCellValue("D".($index+6),$row['writing_count'] );
         $sheet->setCellValue("I".($index+6),$row['cc'] );
       }
     // set border
     $this->xls->getActiveSheet()->getStyle('A1:I'.$i)->applyFromArray($BStyle);
     $sheet->getStyle('A1:I'.$i)->getAlignment()->setWrapText(true);
     $sheet->getRowDimension(2)->setRowHeight(-1);
   }

   private function simpleJournal($searchModel) {
     $xls = new PHPExcel();
     $this->fileName = 'Журнал_обращений_'.date('l jS \of F Y h:i:s A').'.xls';

     $BStyle = [
       'borders' => [
         'allborders' => [
           'style' => PHPExcel_Style_Border::BORDER_THIN
         ]
       ]
     ];

     $xls->setActiveSheetIndex(0);
     $sheet = $xls->getActiveSheet();

     $sheet->getRowDimension(1)->setRowHeight(30);
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

     $modelReqs = $searchModel->getDataModel(null)->all();
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

     $this->xls = $xls;
   }

   public function download() {

     header('Content-Type: application/vnd.ms-excel');
     header('Content-Disposition: attachment;filename='.$this->fileName);

     $objWriter = new PHPExcel_Writer_Excel5($this->xls);
     $objWriter->save('php://output');
   }

}
