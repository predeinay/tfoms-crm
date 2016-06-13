<?php

namespace common\models;

class refCommon extends \yii\db\ActiveRecord
{    
    
    public static function tableName()
    {
        return 'ref_common';
    }
    
    public function attributeLabels()
    {
        return [
            'ref_id' => '',
            'type' => 'Название справочника',
            'text' => 'Значение',
        ];
    }
    
    public function rules() {
        
        return [
            
            [['text','type'], 'required'],
            [['ref_id'], 'number'],
            
        ];
        
    }
    
    public static function getRefByName($type) {
        
        return self::findAll(['type' => $type]);
        
    }

    public static function getRefResult($kind_ref_id) {
        
        $kindModel = refCommon::findOne($kind_ref_id);
        
        if ($kindModel) {
            
            $commonArrTypes = [ 'Консультация' => 'Результат обращения (консультации)',
                                'Жалоба' => 'Результат обращения (жалобы)',
                                'Заявление' => 'Результат обращения (заявления)'];
            
            return refCommon::find()->where(['type' => $commonArrTypes[$kindModel->text]]);
            
        }
        
        return refCommon::find()->where('1=2');
        
    }
    
    // Возврат массива типов справочников для построения LOV
    public static function getCommonTypesArr() {
                
        return [
            
            'Форма обращения' => 'Форма обращения',
            'Путь поступления' => 'Путь поступления',
            'Вид обращения' => 'Вид обращения',
            'Статус обращения' => 'Статус обращения',
            'Тип организации' => 'Тип организации',
            'Результат обращения (жалобы)' => 'Результат обращения (для жалоб)',
            'Результат обращения (консультации)' => 'Результат обращения (консультации)',
            'Результат обращения (заявления)'=> 'Результат обращения (заявления)'
            
        ];
    }
    
}
