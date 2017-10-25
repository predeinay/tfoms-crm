<?php

namespace backend\models\request;

use Yii;
use common\models\Requests;
use yii\data\ActiveDataProvider;
use yii\web\Session;

class RequestSearch extends Requests {

  public $status_ref_id;
  public $company_id;
  public $form_ref_id;
  public $way_ref_id;
  public $kind_ref_id;
  public $reason_id;
  public $from_date;
  public $to_date;
  public $created_by;
  public $surname;
  public $name;
  public $patronymic;

  public $filterCount = 0;

  public function rules() {

      return [
          [['status_ref_id',
            'company_id',
            'claim_company_id',
            'form_ref_id',
            'way_ref_id',
            'kind_ref_id',
            'reason_id',
            'created_by',
            'from_date','to_date',
            'surname','name','patronymic',
            'executed_by',
            'result_ref_id',
            'filterCount'], 'safe'],
      ];
  }

  public function search($params) {

            $provider = new ActiveDataProvider([
                                    'query' => $this->getDataModel($params),
                                    'pagination' => [
                                        'pageSize' => 20,
                                    ],
                                ]);

    return $provider;
  }

  public function getDataModel($params) {

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

            $this->loadSearchParams($params);

            $reqModel->andFilterWhere(['requests.company_id' =>  $this->company_id]);
            $reqModel->andFilterWhere(['requests.claim_company_id' =>  $this->claim_company_id]);
            $reqModel->andFilterWhere(['requests.status_ref_id' =>  $this->status_ref_id]);
            $reqModel->andFilterWhere(['requests.form_ref_id' =>  $this->form_ref_id]);
            $reqModel->andFilterWhere(['requests.way_ref_id' =>  $this->way_ref_id]);
            $reqModel->andFilterWhere(['requests.kind_ref_id' =>  $this->kind_ref_id]);
            $reqModel->andFilterWhere(['requests.reason_id' =>  $this->reason_id]);
            $reqModel->andFilterWhere(['requests.result_ref_id' =>  $this->result_ref_id]);
            $reqModel->andFilterWhere(['requests.created_by' =>  $this->created_by]);

            $reqModel->andFilterWhere(['requests.executed_by' =>  $this->executed_by]);

            $reqModel->andFilterWhere(['like','requests.name', $this->name ]);
            $reqModel->andFilterWhere(['like','requests.patronymic', $this->patronymic]);
            $reqModel->andFilterWhere(['like','requests.surname', $this->surname]);
              //var_dump( $reqModel->createCommand()->getRawSql() );
              //exit();
            if ( $this->from_date != '') {
              $reqModel->andFilterWhere([ '>=','DATE(requests.created_on)' ,\Yii::$app->myhelper->to_date($this->from_date) ]);
            }
            if ( $this->to_date != '') {
              $reqModel->andFilterWhere([ '<=','DATE(requests.created_on)' ,\Yii::$app->myhelper->to_date($this->to_date) ]);
            }

    return $reqModel;
  }

  public static function clearSessionFilter() {
    Yii::$app->session->set('request_search_model','');
    Yii::$app->session->set('request_search_model_filter_count','');
  }

  public function loadSearchParams($params) {
    if ( $this->load($params) && $this->validate() ) {
        $this->filterCount = count(array_filter( $params['RequestSearch'] ));
        Yii::$app->session->set('request_search_model_filter_count',$this->filterCount);
        Yii::$app->session->set('request_search_model',$params);
    } else {
        $this->load( Yii::$app->session->get('request_search_model') );
        $this->filterCount = Yii::$app->session->get('request_search_model_filter_count');
    }
  }

}
