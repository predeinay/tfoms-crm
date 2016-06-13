<?php

namespace backend\models;

use common\models\refCommon;
use yii\data\ActiveDataProvider;

// Модель для поиск среди общего справочника
class refCommonSearch extends refCommon {
    
    public $type;
    public $text;
    
    public function rules() {
        
        return [
            
            [['text'], 'safe'],
            [['type'], 'safe'],
            
        ];
        
    }
    
    public function search($params) {
        
        $commonModel = refCommon::find();
        
        $provider = new ActiveDataProvider([
                        'query' => $commonModel,
                        'pagination' => [
                            'pageSize' => 50,
                        ],
                    ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $provider;
        }
        
        $commonModel->andFilterWhere(['type' => $this->type ]);
        $commonModel->andFilterWhere(['like','text', $this->text ]);
        
        return $provider;
        
    }
    
}