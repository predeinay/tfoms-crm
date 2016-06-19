<?php

namespace common\models;

use yii\db\ActiveRecord;

class reqComment extends ActiveRecord {
    
    public static function tableName()
    {
        return 'comments_bind';
    }
    
    public function attributeLabels()
    {
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
        ];
        
    }
    
}