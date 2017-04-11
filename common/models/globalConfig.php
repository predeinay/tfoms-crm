<?php

namespace common\models;

class globalConfig extends \yii\db\ActiveRecord {

  public static function tableName() {
      return 'global_config';
  }

  public function attributeLabels()
  {
      return [
          'param' => 'Название параметра',
          'value' => 'Значение',
      ];
  }

  public function rules() {

      return [
          [['param','value'], 'required']
      ];

  }

}
