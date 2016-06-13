<?php

namespace frontend\models;
use yii\web\Session;

class Call extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calls';
    }

    /*public function attributeLabels()
    {
        return [
            'call_id' => '',
            'uid' => 'Код звонка телефонии',
            'created_on' => 'Дата поступления',
            'phone_aoh' => 'Номер АОН'
        ];
    }*/
    
    public function rules() {
        
        return [
            
            ['call_uid' , 'string'],
            ['created_on', 'safe'],
            ['phone_aoh', 'string']
            
        ];
        
    }
    
    public function setInSession() {
        
        $session = new Session();
        $session->open();
        
        $session['phone_aoh'] = $this->phone_aoh;
        $session['call_uid'] = $this->call_uid;
        $session['call_id'] = $this->call_id;
    }
    
}