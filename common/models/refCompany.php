<?php

namespace common\models;

use Yii;
use common\models\refCommon;
use yii\db\Expression;

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
    
    public static function getClaimCompanies($isActual = false) {
        $model = self::find()->where(
                        [ 'not in','type_ref_id',
                                [ refCommon::find()->where(
                                        ['text' => 'ТФОМС',
                                         'type' => 'Тип организации']
                                  )->one()->ref_id
                                ]
                        ]
                    );
        $model = $isActual?$model->andWhere(['or',
                                ['<=','date_start',new Expression('NOW()')],['date_start' => null]])
                             ->andWhere(['or',
                                ['>=','date_end',new Expression('NOW()')],['date_end' => null]]) : $model;
        return $model->all();
    }
    
    public static function getNotMed($isActual = false) {
        $model = refCompany::find()->where(
                        [ 'not in','type_ref_id',
                                [ refCommon::find()->where(
                                        ['text' => 'МО',
                                         'type' => 'Тип организации']
                                  )->one()->ref_id
                                ]
                        ]
                    );
        $model = $isActual?$model->andWhere(['or',
                                ['<=','date_start',new Expression('NOW()')],['date_start' => null]])
                             ->andWhere(['or',
                                ['>=','date_end',new Expression('NOW()')],['date_end' => null]]) : $model;
        return $model->all();
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