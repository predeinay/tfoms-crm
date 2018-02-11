<?php

namespace backend\models\upload;

use common\models\refCompany;
use common\models\refCommon;
use backend\models\upload\UploadBase;
use DateTime;

class UploadCompanyFile extends UploadBase {

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xml'],
        ];
    }

    public function parseMedOrgXml() {

        // найдем тип Мед организации
        $modelType = refCommon::getRefByName('Тип организации');

        foreach ($modelType as $model) {

            // Если найден тип в справочнике
            if ($model->text == 'МО') {

                // читаем файл
                $xmlString = file_get_contents($this->file_path .'/'. $this->file_name);
                // конвертим в XML
                $xml = simplexml_load_string($xmlString);

                // для каждого REC элемента
                foreach($xml->REC as $obj) {
                    $dStart = DateTime::createFromFormat('d.m.Y', $obj['D_START']);
                    $dEnd = DateTime::createFromFormat('d.m.Y', $obj['DATE_E']);
                    //var_dump($dStart->format('Y-m-d'));
                    //var_dump($dEnd->format('Y-m-d'));
                    //exit();
                    $companyModel = refCompany::find()->where([
                                                // преобразуем mcod из SimpleXmlElement в строку
                                                'company_code' => $obj['MCOD']->__toString(),
                                                'type_ref_id' => $model->ref_id
                                                ])->one();
                    if (!$companyModel) {
                        $companyModel = new refCompany();
                    } 
                    $companyModel->company_name = $obj['NAM_MOP'];
                    $companyModel->company_short_name = $obj['NAM_MOK'];
                    $companyModel->company_code = $obj['MCOD'];
                    $companyModel->type_ref_id = $model->ref_id;
                    $companyModel->date_start = $dStart->format('Y-m-d');
                    $companyModel->date_end = $dEnd->format('Y-m-d');
                    $companyModel->save();

                }

                return true;

            }
        }

        return false;
    }
}
