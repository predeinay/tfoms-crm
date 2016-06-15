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
        
        $commonArrTypes = [ 'КОНСУЛЬТАЦИЯ' => 'Результат обращения (консультации)',
                            'ЖАЛОБА' => 'Результат обращения (жалобы)',
                            'ЗАЯВЛЕНИЕ' => 'Результат обращения (заявления)'];
        
        $kindModel = refCommon::findOne($kind_ref_id);
        
        if ($kindModel) {
            
            if (array_key_exists( mb_strtoupper($kindModel->text,"UTF-8"), $commonArrTypes )) {
            
                return refCommon::find()->where([
                    'type' => $commonArrTypes[
                                mb_strtoupper($kindModel->text,"UTF-8")
                              ]
                ]);
            }
            
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
