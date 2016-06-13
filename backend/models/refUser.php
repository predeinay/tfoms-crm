<?php

namespace backend\models;

class refUser extends \yii\db\ActiveRecord
{    
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
            'user_name' => 'Имя пользователя'
        ];
    }
    
    public function rules() {
        
        return [
            
            [['user_name','password','login'], 'required'],
            [['user_id'], 'number'],
            
        ];
        
    }
    
}