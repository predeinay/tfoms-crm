<?php

namespace common\models;

class refReason extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_reasons';
    }
    
    public function attributeLabels()
    {
        return [
            'reason_id' => '',
            'reason_text' => 'Описание сути',
            'reason_code' => 'Код сути',
            'kind_ref_id' => 'Вид обращения'
        ];
    }
    
    public function rules() {
        
        return [
            
            [['reason_text','reason_code','kind_ref_id'], 'required'],
            [['reason_id','kind_ref_id'], 'number'],
            
        ];
        
    }
    
    public static function getAll() {
        
        return self::find()->all();
        
    }
    
    // Relation to Common reference
    public function getReasonType() {
        
        return $this->hasOne(refCommon::className(), ['ref_id' => 'kind_ref_id']);
        
    }
    
}