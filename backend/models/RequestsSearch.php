<?php

namespace backend\models;

use common\models\Requests;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class RequestsSearch extends Requests {
    
    public $note;
    public $kind_text;
    public $fio_polis;
    public $kind_ref_id;
    public $created_on;
    public $status_ref_id;
    public $reason_id;
    
    public function rules() {
        
        return [
            [['note','kind_text','fio_polis','kind_ref_id','created_on','status_ref_id','reason_id'], 'safe'],
        ];
    }
    
    public function search($params) {
        
        $query = new Query();
        
        $query->select('r.created_on,
                        r.req_id,
                        rr.reason_text,
                        way.text way_text,
                        kind.text kind_text,
                        form.text form_text,
                        status.text status_text,
                        note,
                        r.surname,
                        r.name,
                        r.patronymic,
                        r.policy_num,
                        r.policy_ser
                        
                        ')
              ->from('requests r')
              ->innerJoin('ref_reasons rr','r.reason_id = rr.reason_id')
              ->innerJoin('ref_common way','r.way_ref_id = way.ref_id')
              ->innerJoin('ref_common kind','r.kind_ref_id = kind.ref_id')
              ->innerJoin('ref_common form','r.form_ref_id = form.ref_id')
              ->leftJoin('ref_common status','r.status_ref_id = status.ref_id');
        
        $provider = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pageSize' => 40,
                        ],
                        'sort' => [
                            'attributes' => [
                                'created_on',
                                ],
                            'defaultOrder' => [
                                'created_on' => SORT_DESC,
                                
                                ]
                            ],
                    ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $provider;
        }
        
        $query->andFilterWhere(['like','created_on',$this->created_on ]);
        $query->andFilterWhere(['like','note', $this->note ]);
        
        $query->andFilterWhere(['status_ref_id' => $this->status_ref_id ]);
        $query->andFilterWhere(['r.kind_ref_id' => $this->kind_ref_id ]);
        $query->andFilterWhere(['r.reason_id' => $this->reason_id ]);
        
        $query->andFilterWhere(['like','CONCAT(surname,\' \',name,\' \',patronymic,\' \',policy_num,\' \',policy_ser)',$this->fio_polis ]);
        
        return $provider;
        
    }
    
}