<?php

namespace common\models;

use Yii;
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
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания'
        ];
    }
    
    public function rules() {
        
        return [
            
            [['company_name','type_ref_id'], 'required'],
            [['company_code','company_short_name'],'safe'],
            [['date_start','date_end'],'safe'],
            [['type_ref_id','company_id'], 'number'],
            
        ];
        
    }
    
    // Relation to Common reference
    
    public function getRef_common() {
        
        return $this->hasOne(refCommon::className(), ['ref_id' => 'type_ref_id']);
        
    }
    
    public function beforeSave($insert) {
        parent::beforeSave($insert);
        
        if ($this->date_start) {
            $this->date_start = Yii::$app->formatter->asDate($this->date_start, 'php:Y-m-d');
        }
        if ($this->date_end) {
            $this->date_end = Yii::$app->formatter->asDate($this->date_end, 'php:Y-m-d');
        }
        return true;
    }
    
}