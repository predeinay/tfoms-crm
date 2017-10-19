<?php

namespace backend\models\upload;

use common\models\refCompany;
use common\models\refCommon;
use backend\models\upload\UploadBase;

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

                    $companyModel = refCompany::find()->where([
                                                // преобразуем mcod из SimpleXmlElement в строку
                                                'company_code' => $obj['MCOD']->__toString(),
                                                'type_ref_id' => $model->ref_id
                                                ])->exists();

                    if (!$companyModel) {

                        $newCompany = new refCompany();

                        $newCompany->company_name = $obj['NAM_MOP'];
                        $newCompany->company_short_name = $obj['NAM_MOK'];
                        $newCompany->company_code = $obj['MCOD'];
                        $newCompany->type_ref_id = $model->ref_id;
                        $newCompany->save();
                    }

                }

                return true;

            }
        }

        return false;
    }
}
