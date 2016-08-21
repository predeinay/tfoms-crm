<?php

namespace common\models;

class refUser extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
      return 'ref_users';
  }

  public static function getAll() {

      return self::find()->all();

  }

}
