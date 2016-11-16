<?php

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\models\refCompany;
use common\models\refCommon;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;
    public $file_name;
    public $file_path;
    
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xml'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $this->file_name = uniqid().$this->file->baseName . '.' . $this->file->extension;
            $this->file_path = 'uploads';
            $this->file->saveAs($this->file_path .'/'. $this->file_name);
            return true;
        } else {
            return false;
        }
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