<?php

namespace backend\models;

class refUser extends \yii\db\ActiveRecord
{
    // Название компании в которой работает
    public $company_name;

    private $levelList = [
      [ 'level' => 1, 'value' => '1 уровень'],
      [ 'level' => 2, 'value' => '2 уровень'],
      [ 'level' => 3, 'value' => '3 уровень'],
    ];

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
            'level' => 'Уровень страхового представителя'
        ];
    }

    public function rules() {

        return [

            [['user_name','password','login','company_id'], 'required'],
            [['user_id','level'], 'number'],

        ];

    }

    public function getLevelList() {
      return $this->levelList;
    }

    public function getLevelValue($level) {
      foreach ($this->levelList as $arrId) {
        if ( $arrId['level'] == $level ) {
          return $arrId['value'];
        }
      }

    }

}
