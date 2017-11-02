<?php

namespace backend\models\request;
use Yii;
use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Writer_Excel5;
use PHPExcel_IOFactory;
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
     
     $this->xls = PHPExcel_IOFactory::load('reports_tpl/my_rep1.xls');
     //$this->xls = new PHPExcel();
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
     /*$sheet->getRowDimension(1)->setRowHeight(60);
     $sheet->getColumnDimension('A')->setWidth(20);

     $sheet->getRowDimension(2)->setRowHeight(20);
     $sheet->getRowDimension(3)->setRowHeight(20);
     $sheet->getRowDimension(4)->setRowHeight(20);
     $sheet->getRowDimension(5)->setRowHeight(20);

     $sheet->getStyle('A7')->getAlignment()->setWrapText(true);
     $sheet->getRowDimension(9)->setRowHeight(-1);
     $sheet->getRowDimension(10)->setRowHeight(-1);
     $sheet->getRowDimension(11)->setRowHeight(-1);

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
     $sheet->setCellValue("G5", '7');$sheet->setCellValue("H5", '8');$sheet->setCellValue("I5", '9');*/
     
     $sql = "
       select kind_text,
              reason_text reason_text,
              reason_code reason_code,
              verbally_tfoms_count,
              write_tfoms_count,
              verbally_tfoms_count + write_tfoms_count tfoms_total,
              verbally_smo_count,
              write_smo_count,
              verbally_smo_count + write_smo_count smo_total,
              cc_all
         from (
            select kind.text kind_text,
                  count(r.req_id) cc_all,
                  rr.reason_text,
                  reason_code,
                  count(req_id) cc,
                  sum(CASE WHEN form.text = 'устно' and resp_type.text = 'ТФОМС' then 1 else 0 END) verbally_tfoms_count,
                  sum(CASE WHEN form.text = 'письменно' and resp_type.text = 'ТФОМС' then 1 else 0 END) write_tfoms_count,
                  sum(CASE WHEN form.text = 'устно' and resp_type.text = 'СМО' then 1 else 0 END) verbally_smo_count,
                  sum(CASE WHEN form.text = 'письменно' and resp_type.text = 'СМО' then 1 else 0 END) write_smo_count,
                  SUBSTRING_INDEX(reason_code,'.',1) a,
                  CASE
                    WHEN instr(reason_code,'.') >0 
                     then substr(reason_code,1+instr(reason_code,'.'))
                  END b
             from ref_reasons rr left join requests r on rr.reason_id = r.reason_id";

      if ($searchModel->claim_company_id) { $sql.= " and r.claim_company_id =  $searchModel->claim_company_id"; }
      if ($searchModel->status_ref_id) {$sql.= " and r.status_ref_id =  $searchModel->status_ref_id";}
      if ($searchModel->form_ref_id) {$sql.= " and r.form_ref_id =  $searchModel->form_ref_id";}
      if ($searchModel->way_ref_id) {$sql.= " and r.way_ref_id =  $searchModel->way_ref_id";}
      if ($searchModel->kind_ref_id) {$sql.= " and r.kind_ref_id =  $searchModel->kind_ref_id";}
      if ($searchModel->reason_id) {$sql.= " and r.reason_id =  $searchModel->reason_id";}
      if ($searchModel->result_ref_id) {$sql.= " and r.result_ref_id =  $searchModel->result_ref_id";}
      if ($searchModel->created_by) {$sql.= " and r.created_by =  $searchModel->created_by";}
      if ($searchModel->executed_by) {$sql.= " and r.executed_by =  $searchModel->executed_by";}
      if ($searchModel->from_date) {$sql.= " and DATE(r.created_on) >=  '".Yii::$app->myhelper->to_date($searchModel->from_date)."'";}
      if ($searchModel->to_date) {$sql.= " and DATE(r.created_on) <=  '".Yii::$app->myhelper->to_date($searchModel->to_date)."'";}

      $sql.="
                                 left join ref_common form on r.form_ref_id = form.ref_id
                                 left join ref_company resp on r.company_id = resp.company_id
                                 left join ref_common resp_type on resp.type_ref_id = resp_type.ref_id
                                 left join ref_common kind on rr.kind_ref_id  = kind.ref_id
            where 1=1 ";

     $sql.="
            group by kind.text, rr.reason_id, rr.reason_text
            
          ) data 
            order by CASE 
                      WHEN kind_text = 'Жалоба' then 1
                      WHEN kind_text = 'Заявление' then 2
                      WHEN kind_text = 'Консультация' then 3
                      else 4
                     END , 
                     cast(a as UNSIGNED),cast(b as DECIMAL(10,6))";

       $data = Yii::$app->db->createCommand($sql)->queryAll();
       
       // preprocessing data
       $verbally_tfoms_count = 0;
       $write_tfoms_count = 0;
       $tfoms_total = 0;
       $verbally_smo_count = 0;
       $write_smo_count = 0;
       $smo_total = 0;
       $cc_all = 0;
       $last_kind = 'last_kind';
       
       $dataAfter = [];
       //$reasonCodes = [];
       //$index = 6;
       foreach ($data as $ind => $row) {
           
            if ($row['kind_text'] == 'Жалоба') {
              $verbally_tfoms_count+=$row['verbally_tfoms_count'];
              $write_tfoms_count+=$row['write_tfoms_count'];
              $tfoms_total+=$row['tfoms_total'];
              $verbally_smo_count+=$row['verbally_smo_count'];
              $write_smo_count+=$row['write_smo_count'];
              $smo_total+=$row['smo_total'];
              $cc_all+=$row['cc_all'];
              $last_kind = $row['kind_text'];
              continue;
            } 
           
           if ($last_kind == 'Жалоба') {
               $dataAfter['2 '] = [
                'reason_text' => 'Жалоба',
                'reason_code' => '2',
                'verbally_tfoms_count' => $verbally_tfoms_count,
                'write_tfoms_count' => $write_tfoms_count,
                'tfoms_total' => $tfoms_total,
                'verbally_smo_count' => $verbally_smo_count,
                'write_smo_count' => $write_smo_count,
                'smo_total' => $smo_total,
                'cc_all' => $cc_all
                ];
           }
           if ( substr_count($row['reason_code'],'.') == 1 ) {
               $groupKey = substr($row['reason_code'],0,strpos($row['reason_code'], '.',0)).' ';
               if (!key_exists($groupKey, $dataAfter)) {
                   $dataAfter[$groupKey] = [
                        'reason_text' => '',
                        'reason_code' => $groupKey,
                        'verbally_tfoms_count' => 0,
                        'write_tfoms_count' => 0,
                        'tfoms_total' => 0,
                        'verbally_smo_count' => 0,
                        'write_smo_count' => 0,
                        'smo_total' => 0,
                        'cc_all' => 0
                     ];
               }
           } else if ( substr_count($row['reason_code'],'.') == 2 ) {
               $groupKey = substr(
                       $row['reason_code'],
                       0,
                       strpos($row['reason_code'], '.',strpos($row['reason_code'], '.',0)+1)
                ).' ';
               if (!key_exists($groupKey, $dataAfter)) {
                $dataAfter[$groupKey] = [
                         'reason_text' => '',
                         'reason_code' => $groupKey,
                         'verbally_tfoms_count' => 0,
                         'write_tfoms_count' => 0,
                         'tfoms_total' => 0,
                         'verbally_smo_count' => 0,
                         'write_smo_count' => 0,
                         'smo_total' => 0,
                         'cc_all' => 0
                      ];
               }
           }
           
           $dataAfter[$row['reason_code'].' '] = [
               'reason_text' => $row['reason_text'],
               'reason_code' => $row['reason_code'],
               'verbally_tfoms_count' => $row['verbally_tfoms_count'],
               'write_tfoms_count' => $row['write_tfoms_count'],
               'tfoms_total' => $row['tfoms_total'],
               'verbally_smo_count' => $row['verbally_smo_count'],
               'write_smo_count' => $row['write_smo_count'],
               'smo_total' => $row['smo_total'],
               'cc_all' => $row['cc_all']
            ];
           $last_kind = $row['kind_text'];
           //$index++;
       }
       // grouping for doubles, unset last added double
       $lastInd = '0';
       foreach ($dataAfter as $ind => $row) {
           //var_dump($lastInd .'=='. $row['reason_code']);
           if ( $lastInd == $row['reason_code'] ) {
               unset($dataAfter[$ind]);
           }
           $lastInd = $ind;
       }
       
       // find in arr x.x.x to sum for x.x
       foreach ($dataAfter as $ind => $row) {
           
           if (substr_count($row['reason_code'],'.') == 2) {
               
               $groupKey = substr($ind,0,strpos($ind, '.',strpos($ind, '.',0)+1)).' ';
               $dataAfter[$groupKey]['verbally_tfoms_count'] = $dataAfter[$groupKey]['verbally_tfoms_count'] + $row['verbally_tfoms_count'];
               $dataAfter[$groupKey]['write_tfoms_count'] = $dataAfter[$groupKey]['write_tfoms_count'] + $row['write_tfoms_count'];
               $dataAfter[$groupKey]['tfoms_total'] = $dataAfter[$groupKey]['tfoms_total'] + $row['tfoms_total'];
               $dataAfter[$groupKey]['verbally_smo_count'] = $dataAfter[$groupKey]['verbally_smo_count'] + $row['verbally_smo_count'];
               $dataAfter[$groupKey]['write_smo_count'] = $dataAfter[$groupKey]['write_smo_count'] + $row['write_smo_count'];
               $dataAfter[$groupKey]['smo_total'] = $dataAfter[$groupKey]['smo_total'] + $row['smo_total'];
               $dataAfter[$groupKey]['cc_all'] = $dataAfter[$groupKey]['cc_all'] + $row['cc_all'];
           }
       }
       
       // find in arr x.x to sum for x
       foreach ($dataAfter as $ind => $row) {
           if (substr_count($row['reason_code'],'.') == 1) {
               
               $groupKey = substr($ind,0,strpos($ind, '.',0)).' ';
               
               $dataAfter[$groupKey]['verbally_tfoms_count'] = $dataAfter[$groupKey]['verbally_tfoms_count'] + $row['verbally_tfoms_count'];
               $dataAfter[$groupKey]['write_tfoms_count'] = $dataAfter[$groupKey]['write_tfoms_count'] + $row['write_tfoms_count'];
               $dataAfter[$groupKey]['tfoms_total'] = $dataAfter[$groupKey]['tfoms_total'] + $row['tfoms_total'];
               $dataAfter[$groupKey]['verbally_smo_count'] = $dataAfter[$groupKey]['verbally_smo_count'] + $row['verbally_smo_count'];
               $dataAfter[$groupKey]['write_smo_count'] = $dataAfter[$groupKey]['write_smo_count'] + $row['write_smo_count'];
               $dataAfter[$groupKey]['smo_total'] = $dataAfter[$groupKey]['smo_total'] + $row['smo_total'];
               $dataAfter[$groupKey]['cc_all'] = $dataAfter[$groupKey]['cc_all'] + $row['cc_all'];
           }
       }
       
       // name empty reasons:
       foreach ($dataAfter as $ind => $row) {
           if ($ind == '3 ') { $dataAfter[$ind]['reason_text'] = 'Заявлений, всего: в т.ч.:'; }
           if ($ind == '3.2 ') { $dataAfter[$ind]['reason_text'] = 'о выборе и замене СМО, в том числе:'; }
           if ($ind == '3.5 ') { $dataAfter[$ind]['reason_text'] = 'о выдаче дубликата (переоформлении) полиса ОМС, в том числе:'; }
           if ($ind == '3.6 ') { $dataAfter[$ind]['reason_text'] = 'другие'; }
           if ($ind == '4 ') { $dataAfter[$ind]['reason_text'] = 'Обращения за консультацией (разъяснением), в том числе:'; }
           if ($ind == '4.1 ') { $dataAfter[$ind]['reason_text'] = 'об обеспечении полисами ОМС, в т.ч.:'; }
           if ($ind == '4.12 ') { $dataAfter[$ind]['reason_text'] = 'о взимании денежных средств за медицинскую помощь по программам ОМС, в том числе:'; }
           
       }
       
       $i = 6;
       foreach ($dataAfter as $ind => $row) {
           if (substr_count($row['reason_code'],'.') == 1) {
               $sheet->setCellValue("A".($i),'  '.$row['reason_text'] );
           } else if (substr_count($row['reason_code'],'.') == 2) {
               $sheet->setCellValue("A".($i),'    '.$row['reason_text'] );
           } else {
               $sheet->setCellValue("A".($i),$row['reason_text'] );
           }
            
            $sheet->setCellValue("B".($i),$ind );
            $sheet->setCellValue("C".($i),$row['verbally_tfoms_count'] );
            $sheet->setCellValue("D".($i),$row['write_tfoms_count'] );
            $sheet->setCellValue("E".($i),$row['tfoms_total'] );
            $sheet->setCellValue("F".($i),$row['verbally_smo_count'] );
            $sheet->setCellValue("G".($i),$row['write_smo_count'] );
            $sheet->setCellValue("H".($i),$row['smo_total'] );
            $sheet->setCellValue("I".($i),$row['cc_all'] );
            $i++;
        }
     // set border
     $this->xls->getActiveSheet()->getStyle('A1:I'.(count($dataAfter)+5))->applyFromArray($BStyle);
     $sheet->getStyle('A1:I'.(count($dataAfter)+5))->getAlignment()->setWrapText(true);
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
