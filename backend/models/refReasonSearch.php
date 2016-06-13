<?php

namespace backend\models;

use common\models\refReason;
use yii\data\ActiveDataProvider;

class refReasonSearch extends refReason {
    
    public $reason_text;
    public $reason_code;
    public $kind_ref_id;
    
    public function rules() {
        
        return [
            [['reason_text','reason_code','kind_ref_id'], 'safe'],
        ];
    }
    
    public function search($params) {
        
        $reasonModel = refReason::find()->with('reasonType');
        
        $provider = new ActiveDataProvider([
                        'query' => $reasonModel,
                        'pagination' => [
                            'pageSize' => 50,
                        ],
                    ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $provider;
        }
        
        $reasonModel->andFilterWhere(['like','reason_text',$this->reason_text ]);
        $reasonModel->andFilterWhere(['like','reason_code', $this->reason_code ]);
        $reasonModel->andFilterWhere(['kind_ref_id' => $this->kind_ref_id ]);
        
        return $provider;
        
    }
    
}