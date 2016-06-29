<?php

namespace backend\models;

class refUser extends \yii\db\ActiveRecord
{    
    // Название компании в которой работает
    public $company_name;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_users';
    }

    public function attributeLabels()
    {
        return [
            'user_id' => '',
            'login' => 'Логин',
            'password' => 'Пароль',
            'user_name' => 'Имя пользователя',
            'company_id' => 'Организация',
        ];
    }
    
    public function rules() {
        
        return [
            
            [['user_name','password','login','company_id'], 'required'],
            [['user_id'], 'number'],
            
        ];
        
    }
    
}