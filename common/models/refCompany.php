<?php

namespace common\models;

use common\models\refCommon;

class refCompany extends \yii\db\ActiveRecord
{    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_company';
    }
    
    public function attributeLabels()
    {
        return [
            'company_id' => '',
            'company_name' => 'Название организации',
            'company_short_name' => 'Краткое название организации',
            'company_code' => 'Внешний код организации',
            'type_ref_id' => 'Тип организации',

        ];
    }
    
    public function rules() {
        
        return [
            
            [['company_name','type_ref_id'], 'required'],
            [['company_code','company_short_name'],'safe'],
            [['type_ref_id','company_id'], 'number'],
            
        ];
        
    }
    
    // Relation to Common reference
    
    public function getRef_common() {
        
        return $this->hasOne(refCommon::className(), ['ref_id' => 'type_ref_id']);
        
    }
    
}