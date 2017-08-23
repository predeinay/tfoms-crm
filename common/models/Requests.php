<?php

namespace common\models;

use common\models\globalConfig;
use Yii;

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

    const CREATED_ON_ERROR = 'Нельзя указывать дату регистрации менее %DATE_COUNT% дней от текущей даты';

    public static function tableName() {
        return 'requests';
    }

    public function attributeLabels() {
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
            'claim_company_id' => 'Организация',

            'executed_by' => 'Исполнитель'
        ];
    }

    public function rules() {

        return [
            [['created_on','reason_id','form_ref_id','kind_ref_id','way_ref_id','status_ref_id','executed_by'], 'required'],
            [['address','note','final_note','reason_custom_text'], 'string', 'max' => 512],
            [['surname','patronymic','name'], 'string', 'max' => 128],
            [['policy_ser','policy_num'],'string','max' => 24],
            [['phone_aoh','phone_contact'],'string','max' => 12],
            [['birth_day'], 'date' , 'format' => 'php:Y-m-d' ],
            [['created_on'], 'date' , 'format' => 'yyyy-M-d H:m:s' ],
            [['phone_aoh_private'],'string', 'max' => 1],
            [['company_id'],'number'],
            [['created_by','result_ref_id'], 'safe'],
            [['claim_company_id'],'required',
                'whenClient' => "function (attribute, value) {
                      return $('#kind_ref_id :selected').text() == 'Жалоба';
                }",
                'when' => function($model) {
                    return $model-> kind_ref_id == refCommon::findOne(
                                                        [ 'type' => 'Вид обращения',
                                                          'text' => 'Жалоба']
                                                    )->ref_id;
                }
            ],
            ['created_on','validateCreatedOn'],

        ];

    }

    public function clearSessionFilter() {
      Yii::$app->session->set('company_id','');
      Yii::$app->session->set('status_ref_id','');
      Yii::$app->session->set('form_ref_id','');
      Yii::$app->session->set('way_ref_id','');
      Yii::$app->session->set('kind_ref_id','');
      Yii::$app->session->set('reason_id','');
      Yii::$app->session->set('created_by','');
      Yii::$app->session->set('from_date','');
      Yii::$app->session->set('to_date','');
      Yii::$app->session->set('surname','');
      Yii::$app->session->set('name','');
      Yii::$app->session->set('patronymic','');
      Yii::$app->session->set('filter_count','');
    }

    public function validateCreatedOn($attribute) {
      if (!$this->req_id) {
        $sysdate = new \DateTime( \Yii::$app->db->createCommand('select NOW() as sdate from dual')->queryOne()['sdate'] );
        $created_on = new \DateTime($this->created_on);
        $globalModel = globalConfig::find()->where(['param' => 'Кол-во дней для регистрации задним числом'])->one();
        $created_on->add(new \ DateInterval('P'.$globalModel->value.'D'));
        if ($sysdate>$created_on) {
          $this->addError($attribute,str_replace('%DATE_COUNT%',$globalModel->value,self::CREATED_ON_ERROR));
        }
      }
    }

}
