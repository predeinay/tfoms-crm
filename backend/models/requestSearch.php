<?php

namespace backend\models;

use Yii;
use common\models\Requests;
use yii\data\ActiveDataProvider;
use yii\web\Session;

class requestSearch extends Requests {

  public $status_ref_id;
  public $company_id;
  public $form_ref_id;
  public $way_ref_id;
  public $kind_ref_id;
  public $reason_id;
  public $from_date;
  public $to_date;
  public $created_by;

  public $filterCount = 0;

  public function rules() {

      return [
          [['status_ref_id',
            'company_id',
            'form_ref_id',
            'way_ref_id',
            'kind_ref_id',
            'reason_id',
            'created_by',
            'from_date','to_date'], 'safe'],
      ];
  }

  public function search($params) {

    $reqModel = Requests::find()
                ->select('req_id, created_on, user_name, company_name, status.text as status_text,
                          surname, name, patronymic, address,
                          note,
                          result.text as result_text,
                          form.text as form_text,
                          way.text as way_text,
                          kind.text as kind_text,
                          ref_reasons.reason_text
                          ')
                ->innerJoin('ref_users', 'requests.created_by = ref_users.user_id')
                ->leftJoin('ref_company', 'requests.company_id = ref_company.company_id')
                ->innerJoin('ref_common status', 'requests.status_ref_id = status.ref_id' )
                ->innerJoin('ref_common form', 'requests.form_ref_id = form.ref_id' )
                ->innerJoin('ref_common way', 'requests.way_ref_id = way.ref_id' )
                ->innerJoin('ref_common kind', 'requests.kind_ref_id = kind.ref_id' )
                ->leftJoin('ref_common result', 'requests.result_ref_id = result.ref_id' )
                ->innerJoin('ref_reasons', 'requests.reason_id = ref_reasons.reason_id' )

                ->orderBy('created_on desc')
                ;

            if ( Yii::$app->user->identity->isTfomsRole( Yii::$app->user->identity->id ) ) {}
            else {
                $reqModel->where(['requests.company_id' => Yii::$app->user->identity->company_id]);
            }

            $provider = new ActiveDataProvider([
                                    'query' => $reqModel,
                                    'pagination' => [
                                        'pageSize' => 20,
                                    ],
                                ]);

            // load the search form data and validate

            if ( !($this->load($params) && $this->validate()) ) {
                  // если пришли без параметров в GET

                  $this->company_id = Yii::$app->session->get('company_id');
                  $this->status_ref_id = Yii::$app->session->get('status_ref_id');
                  $this->form_ref_id = Yii::$app->session->get('form_ref_id');
                  $this->way_ref_id = Yii::$app->session->get('way_ref_id');
                  $this->kind_ref_id = Yii::$app->session->get('kind_ref_id');
                  $this->reason_id = Yii::$app->session->get('reason_id');
                  $this->created_by = Yii::$app->session->get('created_by');
                  $this->from_date = Yii::$app->session->get('from_date');
                  $this->to_date = Yii::$app->session->get('to_date');


                  //return $provider;
            } else {

              // Если GET параметры прилетели

              Yii::$app->session->set('company_id',$this->company_id);
              Yii::$app->session->set('status_ref_id',$this->status_ref_id);
              Yii::$app->session->set('form_ref_id',$this->form_ref_id);
              Yii::$app->session->set('way_ref_id',$this->way_ref_id);
              Yii::$app->session->set('kind_ref_id',$this->kind_ref_id);
              Yii::$app->session->set('reason_id',$this->reason_id);
              Yii::$app->session->set('created_by',$this->created_by);
              Yii::$app->session->set('from_date',$this->from_date);
              Yii::$app->session->set('to_date',$this->to_date);
              Yii::$app->session->set('filter_count', count(array_filter( $params['requestSearch'] )) );
            }

            /*echo "<pre>";
            var_dump($params['requestSearch']);
            echo "</pre>";*/
            //exit();
            //array_filter($params['requestSearch'], function($x) { return !empty($x); });


            $reqModel->andFilterWhere(['requests.company_id' =>  $this->company_id]);
            $reqModel->andFilterWhere(['requests.status_ref_id' =>  $this->status_ref_id]);
            $reqModel->andFilterWhere(['requests.form_ref_id' =>  $this->form_ref_id]);
            $reqModel->andFilterWhere(['requests.way_ref_id' =>  $this->way_ref_id]);
            $reqModel->andFilterWhere(['requests.kind_ref_id' =>  $this->kind_ref_id]);
            $reqModel->andFilterWhere(['requests.reason_id' =>  $this->reason_id]);
            $reqModel->andFilterWhere(['requests.created_by' =>  $this->created_by]);

            if ( $this->from_date != '') {
              $reqModel->andFilterWhere([ '>=','requests.created_on' ,\Yii::$app->myhelper->to_date($this->from_date) ]);
            }
            if ( $this->to_date != '') {
              $reqModel->andFilterWhere([ '<=','requests.created_on' ,\Yii::$app->myhelper->to_date($this->to_date) ]);
            }

    return $provider;
  }

}
