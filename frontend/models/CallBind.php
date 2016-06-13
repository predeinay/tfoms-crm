<?php

namespace frontend\models;

class CallBind extends \yii\db\ActiveRecord
{    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calls_bind';
    }
    
}