<?php

namespace backend\models;

use yii\data\ActiveDataProvider;

class Uploads extends \yii\db\ActiveRecord {

    // Имя пользователь, загрузившего файл
    public $user_name;

    public static function tableName() {
        return 'uploads';
    }

    public function attributeLabels() {
        return [ 'file_id' => '',
                 'file_name' => 'Имя файла',
                 'file_path' => 'Путь к файлу',
                 'request_id' => 'Номер заявки',
                 'created_by' => 'Пользователь',
                 'created_on' => 'Дата загрузки' ];
    }

    public function rules() {
        return [ [['file_name','file_path','request_id','created_by','created_on'], 'required'],
                 [['file_id'], 'number'] ];
    }

    // вернет провайдер по указанному ID заявки
    public static function getDataProvider($id) {
      $query = self::find()
                   ->select('file_id, request_id, file_name, created_on, user_name, file_path')
                   ->innerJoin('ref_users', 'uploads.created_by = ref_users.user_id')
                   ->where(['uploads.request_id' => $id])
                   ->orderBy('uploads.created_on');

      return new ActiveDataProvider([
                              'query' => $query,
                              'pagination' => [
                                  'pageSize' => 100,
                              ],
                          ]);
    }

}
