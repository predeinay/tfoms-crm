<?php

namespace backend\controllers;

use backend\controllers\MainController;

use Yii;

use yii\db\Expression;

use common\models\refCommon;
use common\models\refReason;
use common\models\Requests;
use common\models\reqComment;
use common\models\refCompany;
use yii\data\ActiveDataProvider;

class RequestController extends MainController {
    
    // Список обращений
    public function actionList() {
        
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
        
        /*$searchModel = new \backend\models\RequestsSearch;
        $provider = $searchModel->search(Yii::$app->request->get());*/
        
        return $this->render(
                    'list_view_Reqs',
                    ['provider' => $provider,
                     /*'searchModel' => $searchModel*/ ]
                );        
    }
    
    // Форма для обращений
    public function actionForm($id = null) {
        
        if (!is_null($id)) {
            $model = Requests::findOne($id);
        } else {
            $model = new Requests();
            $model->created_on = Yii::$app->db->createCommand('select NOW() as sdate from dual')->queryOne()['sdate'];
            $model->company_id = Yii::$app->user->identity->company_id;
            
            // Get default status for new request
            $defaultStatus = refCommon::findOne(
                                ['type' => 'Статус обращения', 
                                 'text' => 'в работе']
                             );
            if ($defaultStatus) {
                $model->status_ref_id = $defaultStatus->ref_id;
            }
        }
        
        return $this->render('form_Req',
                             ['model' => $model,
                              'modelForm' => refCommon::getRefByName('Форма обращения'),
                              'modelWay' => refCommon::getRefByName('Путь поступления'),
                              'modelKind' => refCommon::getRefByName('Вид обращения'),
                              'modelStatus' => refCommon::getRefByName('Статус обращения'),
                              'modelResult' => refCommon::getRefResult($model->kind_ref_id)->all(),
                              'modelReason' => refReason::findAll(['kind_ref_id' => $model->kind_ref_id]),
                              'modelCompany' => refCompany::find()->all(),
                              'action' => is_null($id) ? 'create' : 'edit']);
    }
    
    // Комментарии
    public function actionComments($id = null) {
        
        $commentModels = reqComment::findAll(['request_id' => $id]);
        
        return $this->render('form_Comments',
                            ['req_id' => $id,
                             'commentModels' => $commentModels ]);
        
    }
    
    // Записи разговоров
    public function actionRecords($id = null) {
        
        return $this->render('form_Records',
                            ['req_id' => $id ]);
        
    }
    
    public function actionCreate() {
        
        $model = new Requests();
        
        $model->created_by = Yii::$app->user->getId();
        $model->created_on = new Expression('NOW()');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save() ) {
            parent::flash(true);
        } else {
            parent::flash(false);
        }
        
        return $this->redirect(['/request/list']);
        
    }
    
    public function actionDelete($id) {
        
        parent::flash( Requests::findOne($id)->delete() );
        
        return $this->redirect(['/request/list']);
    }
    
    public function actionUpdate($id) {
        
        $model = Requests::findOne($id);
                
        if ( $model->load(Yii::$app->request->post()) ) {
            
        if ( $model->validate() && $model->save() ) {
                parent::flash(true);
            } else {
                parent::flash(false);
            }
        }
        
        return $this->redirect(['/request/list']);

    }
    
    public function actionSubreason() {
        
        $kind_ref_id = Yii::$app->request->post()['depdrop_all_params']['kind_ref_id'];
        
        $reasonModel = refReason::find()->where(['kind_ref_id' => $kind_ref_id])->asArray()->all();
        
        $hmap = \yii\helpers\ArrayHelper::map($reasonModel,'reason_id','reason_text');
        $out = [];
        foreach ($hmap as $key => $value ) {
            $out[] = [ 'id' => $key, 'name' => $value ];
        }
        
        //var_dump($out);
        echo \yii\helpers\Json::encode(
            [
             'output' =>  $out,
             'selected' => ''
            ]);
        
    }
    
    
    public function actionSubresult() {
        
        $kind_ref_id = Yii::$app->request->post()['depdrop_all_params']['kind_ref_id'];

        $resModel = refCommon::getRefResult($kind_ref_id)->asArray()->all();
        
        $hmap = \yii\helpers\ArrayHelper::map($resModel,'ref_id','text');
        $out = [];
        foreach ($hmap as $key => $value ) {
            $out[] = [ 'id' => $key, 'name' => $value ];
        }
        
        echo \yii\helpers\Json::encode(
            [
             'output' =>  $out,
             'selected' => ''
            ]);
        
    }
    
    public function actionIsCustomReason() {
        
        if (Yii::$app->request->post()['reason_id']) {
            
            $reason = refReason::findOne(Yii::$app->request->post());
            return \yii\helpers\Json::encode(
                    ['custom_reason_flag' => $reason->custom_text_flag]
                );
        }
        
        return \yii\helpers\Json::encode(
                    ['custom_reason_flag' => 0]
                );
        
    }
    
}