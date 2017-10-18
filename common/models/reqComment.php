<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class reqComment extends ActiveRecord {

    public $user_name;

    public static function tableName() {
        return 'comments_bind';
    }

    public function attributeLabels() {
        return [
            'comment_id' => '',
            'comment' => 'Комментарий',
            'created_by' => 'Пользователь',
            'request_id' => 'Код обращения',
            'created_on' => 'Дата комментария'
        ];
    }

    public function rules() {
        return [
            [['comment'],'string', 'max' => 512],
            [['comment','created_by','request_id','created_on'], 'required'],
            [['created_by','request_id'], 'number'],
            [['user_name'],'safe']
        ];

    }

    public static function getDataProvider($id) {
      $query = self::find()
                   ->select('request_id, comment, created_on, user_name')
                   ->innerJoin('ref_users', 'comments_bind.created_by = ref_users.user_id')
                   ->where(['comments_bind.request_id' => $id])
                   ->orderBy('comments_bind.created_on');

      return new ActiveDataProvider([
                              'query' => $query,
                              'pagination' => [
                                  'pageSize' => 100,
                              ],
                          ]);

    }
}
