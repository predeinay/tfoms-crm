<?php

namespace common\models;

class refUser extends \yii\db\ActiveRecord
{

  public static function tableName()
  {
      return 'ref_users';
  }

  // get emp by params
  public static function getAll($companyId = null, $withLevel = null) {

    // user model
    $users = null;

    if ($withLevel) {
      // need add level info to LOV
      $levelSelect = [ 'concat(user_name,
                          CASE WHEN level is not null
                            then concat(" уровень представителя " ,level)
                             else ""
                          END) as user_name',
                        'user_id'];
      if ($companyId == null) {
        $users = self::find()->select($levelSelect);
      } else {
        $users = self::find()->select($levelSelect)->where(['company_id' => $companyId]);
      }
    } else {
      if ($companyId == null) {
        $users = self::find();
      } else {
        $users = self::find()->where(['company_id' => $companyId]);
      }
    }

    return $users->all();
  }

}
