<?php

namespace common\models;

class Requests extends \yii\db\ActiveRecord
{

    // relations data for ListView
    public $user_name;
    public $company_name;
    public $status_text;
    public $form_text;
    public $way_text;
    public $kind_text;
    public $reason_text;
    public $result_text;


    public static function tableName()
    {
        return 'requests';
    }

    public function attributeLabels()
    {
        return [

            'req_id' => '',

            'created_on' => 'Дата поступления',
            'created_by' => 'Кто создал обращение',
            'record' => 'Запись разговора',

            'birth_day' => 'Дата рождения',
            'address' => 'Адрес обратившегося',
            'reason_id' => 'Суть обращения',
            'reason_custom_text' => 'Текстовое описание сути',
            'form_ref_id' => 'Форма обращения',
            'kind_ref_id' => 'Вид обращения',
            'way_ref_id' => 'Путь поступления',
            'result_ref_id' => 'Результат',
            'status_ref_id' => 'Статус',

            'note' => 'Описание обращения',

            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'name' => 'Имя',

            'policy_num' => 'Номер полиса',
            'policy_ser' => 'Серия полиса',

            'phone_aoh' => 'АОН',
            'phone_contact' => 'Контактный телефон',
            'phone_aoh_private' => 'Служебный телефон',

            'final_note' => 'Принятые меры',
            'company_id' => 'Зона ответственности',
        ];
    }

    public function rules() {

        return [
            [['created_on','reason_id','form_ref_id','kind_ref_id','way_ref_id','status_ref_id'], 'required'],
            [['address','note','final_note','reason_custom_text'], 'string', 'max' => 512],
            [['surname','patronymic','name'], 'string', 'max' => 128],
            [['policy_ser','policy_num'],'string','max' => 24],
            [['phone_aoh','phone_contact'],'string','max' => 12],
            [['birth_day'], 'date' , 'format' => 'php:Y-m-d' ],
            [['created_on'], 'date' , 'format' => 'yyyy-M-d H:m:s' ],
            [['phone_aoh_private'],'string', 'max' => 1],
            [['company_id'],'number'],
            [['created_by','result_ref_id'], 'safe']

        ];

    }

}
